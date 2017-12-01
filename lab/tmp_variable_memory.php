<?php
/**
 * test not use intermediate variable
 *
 */
class Test
{
    /**
     * dont init the variable, save memory
     */
    protected $t;

    public function t()
    {
       if (! $this->t) {
           /**
            * dont use intermediate variable, save memory
            *
            * example: $tmp = ['a','b','c']; $this->t = $tmp;
            */
            $this->t = [
                'a',
                'b',
                'c',
            ];
        }

        return $this->t;
    }
}

print_r( (new Test())->t() );

echo memory_get_usage() . PHP_EOL;
