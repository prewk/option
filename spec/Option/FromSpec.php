<?php

namespace spec\Prewk\Option;

use PhpSpec\ObjectBehavior;

class FromSpec extends ObjectBehavior
{
    function it_creates_a_some_from_a_set_thing()
    {
        $this::nullable("foo")->unwrap()->shouldBe("foo");
    }

    function it_creates_a_none_from_an_unset_thing()
    {
        $this::nullable(null)->isNone()->shouldBe(true);
    }

    function it_creates_a_some_from_an_existing_key()
    {
        $this::key(["foo" => 123], "foo")->unwrap()->shouldBe(123);
        $this::key(["foo" => null], "foo")->unwrap()->shouldBe(null);
    }

    function it_creates_a_none_from_a_missing_key()
    {
        $this::key(["foo" => 123], "bar")->isNone()->shouldBe(true);
    }

    function it_creates_a_some_from_a_non_empty_value()
    {
        $this::emptyable(["something"])->unwrap()->shouldBe(["something"]);
        $this::emptyable(true)->unwrap()->shouldBe(true);
        $this::emptyable(1)->unwrap()->shouldBe(1);
        $this::emptyable("1")->unwrap()->shouldBe("1");
    }

    function it_creates_a_none_from_an_empty_value()
    {
        $this::emptyable("")->isNone()->shouldBe(true);
        $this::emptyable(null)->isNone()->shouldBe(true);
        $this::emptyable(false)->isNone()->shouldBe(true);
        $this::emptyable(0)->isNone()->shouldBe(true);
        $this::emptyable("0")->isNone()->shouldBe(true);
        $this::emptyable([])->isNone()->shouldBe(true);
    }
}
