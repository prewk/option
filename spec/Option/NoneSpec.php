<?php

namespace spec\Prewk\Option;

use Exception;
use Prewk\Option\None;
use PhpSpec\ObjectBehavior;
use Prewk\Option\OptionException;
use Prewk\Option\Some;
use Prewk\Result\Err;

class NoneSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(None::class);
    }

    function it_isnt_some()
    {
        $this->isSome()->shouldBe(false);
    }

    function it_is_none()
    {
        $this->isNone()->shouldBe(true);
    }

    function it_expects_with_its_value()
    {
        $msg = new Exception("error");
        $this->shouldThrow($msg)->during("expect", [$msg]);
    }

    function it_doesnt_unwrap()
    {
        $this->shouldThrow(Exception::class)->during("unwrap");
    }

    function it_unwrapOrs()
    {
        $this->unwrapOr("value")->shouldBe("value");
    }

    function it_unwrapOrElses()
    {
        $this->unwrapOrElse(function() {
            return "value";
        })->shouldBe("value");
    }

    function it_doesnt_map()
    {
        $this->map(function() {})->shouldBe($this);
    }

    function it_mapOrs()
    {
        $this->mapOr("value", function() {})->shouldBe("value");
    }

    function it_mapOrElses()
    {
        $this->mapOrElse(
            function() {
                return "value";
            },
            function($value) {

            }
        )->shouldBe("value");
    }

    function it_returns_an_iterator()
    {
        $this->iter()->shouldBe([]);
    }

    function it_ands()
    {
        $this->and(new Some("ignored"))->shouldHaveType(None::class);
    }

    function it_andThens()
    {
        $this->andThen(function() {})->shouldHaveType(None::class);
    }

    function it_ors()
    {
        $this->or(new Some("value"))->unwrap()->shouldBe("value");
    }

    function it_orElses()
    {
        $this->orElse(function() {
            return new Some("value");
        })->unwrap()->shouldBe("value");
    }

    function it_throws_on_orElse_closure_return_type_mismatch()
    {
        $this->shouldThrow(OptionException::class)->during("orElse", [function() {
            return "Not an option";
        }]);
    }

    function it_converts_into_err_with_okOr()
    {
        $result = $this->okOr("error");

        $result->shouldHaveType(Err::class);
        $result->unwrapErr()->shouldBe("error");
    }

    function it_converts_into_err_with_okOrElse()
    {
        $result = $this->okOrElse(function() { return "error"; });

        $result->shouldHaveType(Err::class);
        $result->unwrapErr()->shouldBe("error");
    }

    function it_unwrapOrElses_with_pass_args()
    {
        $this->with("foo", "bar")->unwrapOrElse(function($foo, $bar) {
            return $foo . $bar;
        })->shouldBe("foobar");
    }

    function it_mapOrElses_with_pass_args()
    {
        $this->with("foo", "bar")->mapOrElse(
            function($foo, $bar) {
                return $foo . $bar;
            },
            function($value) {

            }
        )->shouldBe("foobar");
    }

    function it_orElses_with_pass_args()
    {
        $this->with("foo", "bar")->orElse(function($foo, $bar) {
            return new Some($foo . $bar);
        })->unwrap()->shouldBe("foobar");
    }

    function it_okOrs_with_pass_args()
    {
        $result = $this->with("foo", "bar")->okOr("error");

        $result->shouldHaveType(Err::class);
        $result->unwrapErr()->shouldBe("error");

        $result->mapErr(function($error, $foo, $bar) {
            return $error . $foo . $bar;
        })->unwrapErr()->shouldBe("errorfoobar");
    }

    function it_okOrElses_with_pass_args()
    {
        $result = $this->with("foo", "bar")->okOrElse(function() { return "error"; });

        $result->shouldHaveType(Err::class);
        $result->unwrapErr()->shouldBe("error");

        $result->mapErr(function($error, $foo, $bar) {
            return $error . $foo . $bar;
        })->unwrapErr()->shouldBe("errorfoobar");
    }
}
