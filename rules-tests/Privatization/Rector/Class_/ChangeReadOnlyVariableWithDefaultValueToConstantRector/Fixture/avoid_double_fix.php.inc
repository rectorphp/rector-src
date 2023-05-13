<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

final class AvoidDoubleFix
{
    private $client;

    public function __construct(int $timeout = 5) {
        $wsdl = 'https://pathtowsdl?wsdl';
        $opts = [
            'connection_timeout' => $timeout,
        ];
        try {
            $this->client = new \SOAPClient($wsdl, $opts);
        } catch (\Exception $e) {
        }
    }

    private function normalize(string $thing): string {
        $opts = [
            'A' => 'Z',
            'B' => 'Y',
        ];

        return $opts[$thing];
    }
}


?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

final class AvoidDoubleFix
{
    private $client;
    /**
     * @var string
     */
    private const WSDL = 'https://pathtowsdl?wsdl';
    /**
     * @var array<string, string>
     */
    private const OPTS = [
        'A' => 'Z',
        'B' => 'Y',
    ];

    public function __construct(int $timeout = 5) {
        $options = [
            'connection_timeout' => $timeout,
        ];
        try {
            $this->client = new \SOAPClient(self::WSDL, $options);
        } catch (\Exception $e) {
        }
    }

    private function normalize(string $thing): string {
        return self::OPTS[$thing];
    }
}


?>
