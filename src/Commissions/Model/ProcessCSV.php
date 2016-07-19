<?php

namespace Commissions\Model;

use Commissions\Entity\OperationEntity;

class ProcessCSV
{
    /**
     * Checking if the given date is correct
     *
     * @param $date
     * @return bool
     */
    private function validateDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);

        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * If the given data is correct new operation object is being created
     *
     * @param $row
     * @param $errorLine
     * @return OperationEntity
     * @throws \Exception
     */
    private function processOperation($row, $errorLine)
    {
        try {
            if (count($row) != 6) {
                throw new \Exception('Wrong amount of input parameters in the line '.$errorLine);
            } elseif (!$this->validateDate($row[0])) {
                throw new \Exception('Wrong DATE in the line '.$errorLine);
            } elseif (!is_numeric($row[1])) {
                throw new \Exception('Wrong ID format in the line '.$errorLine);
            } elseif (!ctype_digit($row[1])) {
                throw new \Exception('ID is not an integer in the line '.$errorLine);
            } elseif ($row[2] !== "natural" && $row[2] !== "juridical") {
                throw new \Exception('Wrong PERSON TYPE in the line '.$errorLine);
            } elseif ($row[3] !== "cash_in" && $row[3] !== "cash_out") {
                throw new \Exception('Wrong TRANSACTION TYPE in the line '.$errorLine);
            } elseif (!is_numeric($row[4])) {
                throw new \Exception('Wrong AMOUNT format in the line '.$errorLine);
            } elseif ($row[5] !== "EUR" && $row[5] !== "USD" && $row[5] !== "JPY") {
                throw new \Exception('Wrong CURRENCY specified in the line '.$errorLine);
            }

            $date = new \DateTime($row[0]);
            $date->format('Y-m-d');

            return new OperationEntity($date, $row[1], $row[2], $row[3], $row[4], $row[5]);

        } catch (\Throwable $t) {
            throw new \Exception($t->getMessage());
        }
    }

    /**
     * Getting all of the operation objects from the file
     *
     * @param $path
     * @return array
     * @throws \Exception
     */
    public function getAllOperations($path)
    {
        $errorLine = 0;
        $operationsArray = [];

        if (file_exists($path)) {
            if (($handle = fopen($path, "r")) !== false) {
                while (($row = fgetcsv($handle)) !== false) {
                    $operation = $this->processOperation($row, ++$errorLine);
                    array_push($operationsArray, $operation);
                }
            }
        } else {
            throw new \Exception('File does not exist!');
        }

        return $operationsArray;
    }
}
