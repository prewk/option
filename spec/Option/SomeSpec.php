<?php

namespace spec\Prewk\Option;

use Exception;
use Prewk\Option;
use Prewk\Option\OptionException;
use Prewk\Option\Some;
use PhpSpec\ObjectBehavior;
use Prewk\Result\Ok;

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

    function it_throws_on_andThen_closure_return_type_mismatch()
    {
        $this->beConstructedWith("foo");
        $this->shouldThrow(OptionException::class)->during("andThen", [function() {
            return "Not an option";
        }]);
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

    function it_converts_into_ok_with_okOr()
    {
        $this->beConstructedWith("value");
        $result = $this->okOr("ignored");

        $result->shouldHaveType(Ok::class);
        $result->unwrap()->shouldBe("value");
    }

    function it_converts_into_ok_with_okOrElse()
    {
        $this->beConstructedWith("value");
        $result = $this->okOrElse(function() {});

        $result->shouldHaveType(Ok::class);
        $result->unwrap()->shouldBe("value");
    }

    function it_maps_with_pass_args()
    {
        $this->beConstructedWith("foo");
        $option = $this->with("bar", "baz")->map(function($foo, $bar, $baz) {
            return $foo . $bar . $baz;
        });

        $option->shouldHaveType(Option::class);
        $option->unwrap()->shouldBe("foobarbaz");
    }

    function it_mapOrs_with_pass_args()
    {
        $this->beConstructedWith("foo");
        $this->with("bar", "baz")->mapOr("ignored", function($foo, $bar, $baz) {
            return $foo . $bar . $baz;
        })->shouldBe("foobarbaz");
    }

    function it_mapOrElses_with_pass_args()
    {
        $this->beConstructedWith("foo");
        $this->with("bar", "baz")->mapOrElse(
            function() {

            },
            function($foo, $bar, $baz) {
                return $foo . $bar . $baz;
            }
        )->shouldBe("foobarbaz");
    }

    function it_andThens_with_pass_args()
    {
        $this->beConstructedWith("foo");
        $option = $this->with("bar", "baz")->andThen(function($foo, $bar, $baz) {
            return new Some($foo . $bar . $baz);
        });

        $option->unwrap()->shouldBe("foobarbaz");
    }

    function it_okOrs_with_pass_args()
    {
        $this->beConstructedWith("foo");
        $result = $this->with("bar", "baz")->okOr("ignored");

        $result->shouldHaveType(Ok::class);
        $result->unwrap()->shouldBe("foo");

        $result->map(function($foo, $bar, $baz) {
            return $foo . $bar . $baz;
        })->unwrap()->shouldBe("foobarbaz");
    }
}
