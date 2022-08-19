<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\FixtureAutoImport;

return new class {
    /**
     * Run the migrations.
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function up(): void
    {
        Schema::create('tagging_tagged', function (Blueprint $table) : void {
            $table->engine = 'InnoDB';
        });
    }
};

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\FixtureAutoImport;

return new class {
    /**
     * Run the migrations.
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function up(): void
    {
        Schema::create('tagging_tagged', static function (Blueprint $table) : void {
            $table->engine = 'InnoDB';
        });
    }
};

?>
