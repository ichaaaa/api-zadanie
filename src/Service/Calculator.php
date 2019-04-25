<?php

namespace App\Service;

class Calculator
{
	public function calculate($price, $rate): float
	{
		return $price / $rate;
	}
}