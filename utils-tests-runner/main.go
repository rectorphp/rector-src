// Command fast-phpunit runs the PHPUnit suite in parallel by splitting test
// classes into N balanced "warm" chunks: each worker boots PHP once and runs
// many test classes in a single process, so the container is built N times
// instead of once per class (as tools that spawn a process per chunk do).
//
// Balancing is by fixture count, since Rector rule tests iterate one assertion
// per .php.inc fixture, so fixture count approximates a class's runtime.
package main

import (
	"flag"
	"fmt"
	"os"
	"os/exec"
	"path/filepath"
	"regexp"
	"runtime"
	"sort"
	"strings"
	"sync"
	"time"
)

type testClass struct {
	path   string
	weight int // fixture count, min 1
}

var fixtureCountRe = regexp.MustCompile(`\.php\.inc$`)

func main() {
	workers := flag.Int("p", runtime.NumCPU(), "number of parallel workers")
	phpunit := flag.String("bin", "vendor/bin/phpunit", "phpunit binary")
	flag.Parse()

	dirs := flag.Args()
	if len(dirs) == 0 {
		dirs = []string{"rules-tests", "tests"}
	}

	classes := discover(dirs)
	if len(classes) == 0 {
		fmt.Fprintln(os.Stderr, "no test classes found")
		os.Exit(1)
	}

	chunks := balance(classes, *workers)

	start := time.Now()
	failed := run(chunks, *phpunit, *workers)
	elapsed := time.Since(start)

	totalFixtures := 0
	for _, c := range classes {
		totalFixtures += c.weight
	}
	fmt.Printf("\n%d classes, %d fixtures, %d chunks, %d workers\n",
		len(classes), totalFixtures, len(chunks), *workers)
	fmt.Printf("wall time: %.2fs\n", elapsed.Seconds())

	if failed > 0 {
		fmt.Printf("FAILED chunks: %d\n", failed)
		os.Exit(1)
	}
	fmt.Println("OK")
}

// discover finds *Test.php files and weights each by sibling Fixture/ file count.
func discover(dirs []string) []testClass {
	var classes []testClass
	for _, dir := range dirs {
		_ = filepath.WalkDir(dir, func(path string, d os.DirEntry, err error) error {
			if err != nil || d.IsDir() || !strings.HasSuffix(path, "Test.php") {
				return nil
			}
			classes = append(classes, testClass{path: path, weight: fixtureWeight(path)})
			return nil
		})
	}
	return classes
}

func fixtureWeight(testPath string) int {
	fixtureDir := filepath.Join(filepath.Dir(testPath), "Fixture")
	entries, err := os.ReadDir(fixtureDir)
	if err != nil {
		return 1
	}
	count := 0
	for _, e := range entries {
		if !e.IsDir() && fixtureCountRe.MatchString(e.Name()) {
			count++
		}
	}
	if count < 1 {
		return 1
	}
	return count
}

// balance greedily packs classes (heaviest first) into n bins, always adding to
// the lightest bin. Minimizes the heaviest chunk, so wall time is bounded by the
// slowest worker rather than by unlucky static splits.
func balance(classes []testClass, n int) [][]testClass {
	sort.Slice(classes, func(i, j int) bool {
		return classes[i].weight > classes[j].weight
	})
	bins := make([][]testClass, n)
	loads := make([]int, n)
	for _, c := range classes {
		min := 0
		for i := 1; i < n; i++ {
			if loads[i] < loads[min] {
				min = i
			}
		}
		bins[min] = append(bins[min], c)
		loads[min] += c.weight
	}
	var out [][]testClass
	for _, b := range bins {
		if len(b) > 0 {
			out = append(out, b)
		}
	}
	return out
}

func run(chunks [][]testClass, bin string, workers int) int {
	sem := make(chan struct{}, workers)
	var wg sync.WaitGroup
	var mu sync.Mutex
	failed := 0

	for idx, chunk := range chunks {
		wg.Add(1)
		go func(idx int, chunk []testClass) {
			defer wg.Done()
			sem <- struct{}{}
			defer func() { <-sem }()

			// Each worker gets its own TMPDIR so Rector's file cache
			// (sys_get_temp_dir()/rector_cached_files) and the fixture temp
			// dumper never race across processes.
			tmp := filepath.Join(os.TempDir(), fmt.Sprintf("fast-phpunit-%d", idx))
			_ = os.MkdirAll(tmp, 0o755)
			defer os.RemoveAll(tmp)

			args := make([]string, 0, len(chunk))
			for _, c := range chunk {
				args = append(args, c.path)
			}
			cmd := exec.Command(bin, args...)
			cmd.Env = append(os.Environ(), "TMPDIR="+tmp)
			out, err := cmd.CombinedOutput()
			if err != nil {
				mu.Lock()
				failed++
				fmt.Printf("chunk FAILED (%d classes):\n%s\n", len(chunk), tail(string(out), 15))
				mu.Unlock()
			}
		}(idx, chunk)
	}
	wg.Wait()
	return failed
}

func tail(s string, lines int) string {
	parts := strings.Split(strings.TrimRight(s, "\n"), "\n")
	if len(parts) > lines {
		parts = parts[len(parts)-lines:]
	}
	return strings.Join(parts, "\n")
}
