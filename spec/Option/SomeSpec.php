<?php

namespace spec\Prewk\Option;

use Exception;
use Prewk\Option;
use Prewk\Option\None;
use Prewk\Option\Some;
use PhpSpec\ObjectBehavior;

class SomeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith("value");
        $this->shouldHaveType(Some::class);
    }

    function it_is_some()
    {
        $this->beConstructedWith("value");
        $this->isSome()->shouldBe(true);
    }

    function it_isnt_none()
    {
        $this->beConstructedWith("value");
        $this->isNone()->shouldBe(false);
    }

    function it_expects()
    {
        $this->beConstructedWith("value");
        $this->expect(new Exception("ignored"))->shouldBe("value");
    }

    function it_unwraps()
    {
        $this->beConstructedWith("value");
        $this->unwrap()->shouldBe("value");
    }

    function it_unwrapOrs()
    {
        $this->beConstructedWith("value");
        $this->unwrapOr("ignored")->shouldBe("value");
    }

    function it_unwrapOrElses()
    {
        $this->beConstructedWith("value");
        $this->unwrapOrElse(function() {})->shouldBe("value");
    }

    function it_maps()
    {
        $this->beConstructedWith("foo");
        $option = $this->map(function($value) {
            return $value . "bar";
        });

        $option->shouldHaveType(Option::class);
        $option->unwrap()->shouldBe("foobar");
    }

    function it_mapOrs()
    {
        $this->beConstructedWith("foo");
        $this->mapOr("ignored", function($value) {
            return $value . "bar";
        })->shouldBe("foobar");
    }

    function it_mapOrElses()
    {
        $this->beConstructedWith("foo");
        $this->mapOrElse(
            function() {

            },
            function($value) {
                return $value . "bar";
            }
        )->shouldBe("foobar");
    }

    function it_returns_an_iterator()
    {
        $this->beConstructedWith("value");
        $this->iter()->shouldBe(["value"]);
    }

    function it_ands()
    {
        $this->beConstructedWith("foo");
        $this->and(new Some("bar"))->unwrap()->shouldBe("bar");
    }

    function it_andThens()
    {
        $otherResult = null;

        $this->beConstructedWith("foo");
        $this->andThen(function($value) use (&$otherResult) {
            $otherResult = new Some($value . "bar");
            return $otherResult;
        })->shouldBe($otherResult);
    }

    function it_ors()
    {
        $this->beConstructedWith("value");
        $this->or(new Some("ignored"))->unwrap()->shouldBe("value");
    }

    function it_orElses()
    {
        $this->beConstructedWith("value");
        $this->orElse(function() {})->unwrap()->shouldBe("value");
    }
}
