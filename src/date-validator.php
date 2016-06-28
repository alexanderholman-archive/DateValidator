<?php

/**
 * Class DateValidator
*/
class DateValidator {

    const NULLEMPTY = 0;
    const FORMAT    = 1;
    const INVALID   = 2;
    const VALID     = 3;
    const NOTSTRING = 4;
    const NOTPAST   = 5;

    /**
     * @var array
     */
    static private $messages = [
        self::NULLEMPTY => 'Date is empty',
        self::FORMAT    => 'Date string entered did not match the pattern DD/MM/YYYY',
        self::INVALID   => 'Date is not valid',
        self::VALID     => 'Date is valid',
        self::NOTSTRING => 'Data transmitted was not a string',
        self::NOTPAST   => 'Date sent was not a historic date'
    ];

    /**
     * @var null|Result;
     */
    static private $result = null;

    /**
     * @param mixed $key
     * @return string
     */
    static public function getMessage( $key ) {
        if ( !key_exists($key, self::$messages) ) return '';
        return static::$messages[$key];
    }

    /**
     * @param null|string $dateString
     * @param bool $detailed
     * @return Result
     */
    static public function validateDate( $dateString = null, $detailed = false ) {
        self::initResult();
        if ( $detailed ) {
            if ( is_null( $dateString ) || empty( $dateString ) ) {
                self::$result->setMessage( self::getMessage( self::NULLEMPTY ) );
                self::$result->setValidity( false );
                return self::$result;
            }
            if ( !is_string( $dateString ) ) {
                self::$result->setMessage( self::getMessage( self::NOTSTRING ) );
                self::$result->setValidity( false );
                return self::$result;
            }
            $regex = '/\d{2}\/\d{2}\/\d{4}/';
            $found = [];
            preg_match_all( $regex, $dateString, $found );
            if ( count( $found[0] ) <> 1 ) {
                self::$result->setMessage( self::getMessage( self::FORMAT ) );
                self::$result->setValidity( false );
                return self::$result;
            }
            $dateParts = explode( '/', $dateString );
            self::$result->setValidity( checkdate( (int) $dateParts[1], (int) $dateParts[0], (int) $dateParts[2] ) );
        } else {
            $format = 'd/m/Y';
            $createdDate = DateTime::createFromFormat($format, $dateString);
            self::$result->setValidity( $createdDate && $createdDate->format($format) === $dateString );
        }
        self::$result->setMessage( self::getMessage( self::$result->isValid() ? self::VALID : self::INVALID ) );
        return self::$result;
    }

    /**
     * @param string $dateString
     * @return Result
     */
    static public function isHistoricalDate( $dateString ) {
        self::initResult();
        $dateString = str_replace( '/', '-', $dateString );
        $inputDate = new DateTime( $dateString );
        $yesterdayDate = new DateTime();
        $yesterdayDate->sub( new DateInterval( 'P1D' ) );
        if ( $inputDate > $yesterdayDate ) {
            self::$result->setValidity( false );
            self::$result->setMessage( self::getMessage( self::NOTPAST ) );
            return self::$result;
        }
        self::$result->setValidity( true );
        self::$result->setMessage( self::getMessage( self::VALID ) );
        return self::$result;
    }

    /**
     * @param null|string $dateString
     * @param bool $detailed If you want the messages to be a little more detailed
     * set to true || If you just want to know if a string is a valid date, set to false
     * @return Result
     */
    static public function validateHistoricalDate( $dateString = null, $detailed = false ) {
        self::$result = self::validateDate( $dateString, $detailed );
        if ( !self::$result->isValid() ) return self::$result;
        self::$result = self::isHistoricalDate( $dateString );
        if ( !self::$result->isValid() ) return self::$result;
        return self::$result;
    }

    /**
     * @param array $tests
     * @return bool
     */
    static public function testValidateHistoricalDate( $tests = [] )
    {
        $defaultTests = [
            0 => [
                'date' => '03/12/1999',
                'valid' => true,
                'message' => self::VALID,
                'detailed' => true
            ],
            1 => [
                'date' => '03/12/2999',
                'valid' => false,
                'message' => self::NOTPAST,
                'detailed' => true
            ],
            2 => [
                'date' => '3/12/1999',
                'valid' => false,
                'message' => self::FORMAT,
                'detailed' => true
            ],
            3 => [
                'date' => '03/31/99',
                'valid' => false,
                'message' => self::FORMAT,
                'detailed' => true
            ],
            4 => [
                'date' => '3/31/99',
                'valid' => false,
                'message' => self::FORMAT,
                'detailed' => true
            ],
            5 => [
                'date' => '03/12/1999/03/12/1999',
                'valid' => false,
                'message' => self::FORMAT,
                'detailed' => true
            ],
            6 => [
                'date' => '12/31/1999',
                'valid' => false,
                'message' => self::INVALID,
                'detailed' => true
            ],
            7 => [
                'date' => '',
                'valid' => false,
                'message' => self::NULLEMPTY,
                'detailed' => true
            ],
            8 => [
                'date' => null,
                'valid' => false,
                'message' => self::NULLEMPTY,
                'detailed' => true
            ],
            9 => [
                'date' => '03/12/1999',
                'valid' => true,
                'message' => self::VALID,
                'detailed' => false
            ],
            10 => [
                'date' => '03/12/2999',
                'valid' => false,
                'message' => self::NOTPAST,
                'detailed' => false
            ],
            11 => [
                'date' => '3/12/1999',
                'valid' => false,
                'message' => self::INVALID,
                'detailed' => false
            ],
            12 => [
                'date' => '03/31/99',
                'valid' => false,
                'message' => self::INVALID,
                'detailed' => false
            ],
            13 => [
                'date' => '3/31/99',
                'valid' => false,
                'message' => self::INVALID,
                'detailed' => false
            ],
            14 => [
                'date' => '03/12/1999/03/12/1999',
                'valid' => false,
                'message' => self::INVALID,
                'detailed' => false
            ],
            15 => [
                'date' => '12/31/1999',
                'valid' => false,
                'message' => self::INVALID,
                'detailed' => false
            ],
            16 => [
                'date' => '',
                'valid' => false,
                'message' => self::INVALID,
                'detailed' => false
            ],
            17 => [
                'date' => null,
                'valid' => false,
                'message' => self::INVALID,
                'detailed' => false
            ],
            18 => [
                'date' => new DateTime( '03/12/1999' ),
                'valid' => false,
                'message' => self::NOTSTRING,
                'detailed' => true
            ]
        ];
        
        $tests = array_merge_recursive($defaultTests, $tests);
        
        foreach ($tests as $test) {
            
            $result = static::validateHistoricalDate( $test['date'], $test['detailed'] );
            
            $testResult = $result->isValid() === $test['valid'] && $result->getMessage() === static::getMessage($test['message']);

            if ( !$testResult ) {
                return false;
            }
            
        }
        
        return true;

    }

    /**
     * set self::$result = new Result if null
     */
    static private function initResult() {
        if ( is_null( self::$result ) ) self::$result = new Result();
    }

}