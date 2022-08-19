<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\FixtureAutoImport;

return new class {
    public function up(): void
    {
        \Some\Framework\Db\FullyQualified\Schema::create('tagging_tagged', function (Blueprint $table) : void {
            $table->engine = 'InnoDB';
        });
    }
};

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\FixtureAutoImport;

use Some\Framework\Db\FullyQualified\Schema;
return new class {
    public function up(): void
    {
        Schema::create('tagging_tagged', static function (Blueprint $table) : void {
            $table->engine = 'InnoDB';
        });
    }
};

?>
