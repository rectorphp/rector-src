# Duplicates

Token-based copy-paste detector, a small clone of [phpcpd](https://github.com/phpcpd-next/phpcpd).

It tokenizes PHP with `token_get_all()`, strips whitespace and comments, then finds exact duplicate token spans with a Rabin-Karp rolling hash.

## Usage

```bash
php utils/duplicates/bin/duplicates.php src rules
```

Options:

- `--min-lines` minimum lines of a clone (default `5`)
- `--min-tokens` minimum tokens of a clone (default `70`)
- `--fuzzy` ignore variable names when matching

Exit code is `1` when clones are found, `0` otherwise.
