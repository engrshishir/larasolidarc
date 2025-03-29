<?php
declare(strict_types=1);

namespace App\Traits;

trait GeneratesUniqueNumberTrait
{
    /**
     * Generates a unique account number.
     *
     * @param string $prefix The prefix to use (default: 'B-')
     * @return string The formatted unique account number
     */
    public function generateUniqueAccountNumber(string $prefix = 'B-'): string
    {
        // Generate a random number with 10 digits
        $randomNumber = mt_rand(1, 9999999999);

        // Format the number as a 10-digit number with leading zeros
        $formattedAccountNumber = sprintf("%s%010d", $prefix, $randomNumber);

        return $formattedAccountNumber;
    }
}
