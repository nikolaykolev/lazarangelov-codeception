<?php

namespace Page;

/**
 * PageObject class for the elements in the measurements page
 * @package Page
 */
class MeasurementsPageObject extends BasePageObject
{

    // include url of current page
    public static $URL = '/10';
    public $measurementUnits = '';
    private $weightUnits = 'kg';
    private $tester;
    public $metricBtn = '#metric-form [data-measurements=metric]';
    public $imperialBtn = '#metric-form [data-measurements=imperial]';
    public $metricSelected = '#metric-form .a-tab--active[data-measurements=metric]';
    public $ageField = '';
    public $heightFtField = 'input[name=height_ft]';
    public $heightInchField = 'input[name=height_inch]';
    public $heightCmField = 'input[name=height_cm]';
    public $weightField = '';
    public $targetWeightField = '';
    public $errorTooltipElements = '.a-input__input--error';
    public $fieldsMarkedAsErrorElements = '[aria-invalid=ture]';


    //In a real situation I could get those strings + numbers from a DB or language files
    public $ageErrorText = 'Please enter a value greater than or equal to 14.';
    public $heightCmErrorText = 'Please enter a value greater than or equal to 135.';
    public $heightFtErrorText = 'Please enter a value greater than or equal to 135.';
    public $heightInchErrorText = 'Please enter a value greater than or equal to 135.';
    public $weightKgErrorText = 'Please enter a value greater than or equal to 40.';
    public $weightLbErrorText = 'Please enter a value greater than or equal to 90.';
    private $greaterOrEqualMsg = 'Please enter a value greater than or equal to ';
    private $lessOrEqualMsg = 'Please enter a value less than or equal to ';
    public $notANumberErrorMsg = 'Please enter a valid number';
    public $fieldRequiredErrorMsg = 'This field is required.';

    /**
     * MeasurementsPageObject constructor.
     * @param $actor
     * @param $measurementUnits
     */
    public function __construct($actor, $measurementUnits)
    {
        $this->measurementUnits = $measurementUnits;
        $this->tester = $actor;
        $this->initElements();
    }

    /**
     * Initialize the web elements for the current selected measurement units
     */
    private function initElements()
    {
        if ($this->measurementUnits == 'imperial') {
            $this->weightUnits = 'lb';
        }
        $this->ageField = 'input[data-measurement=' . $this->measurementUnits . '][name=age]';
        $this->weightField = 'input[data-measurement=' . $this->measurementUnits . '][name=weight_' . $this->weightUnits . ']';
        $this->targetWeightField = 'input[data-measurement=' . $this->measurementUnits . '][name=weight_target_' . $this->weightUnits . ']';
    }

    /**
     * Choose metric units. The form's elements are reinitialized as the units play a role in the selectors.
     */
    public function chooseMetricUnits()
    {
        if (!$this->isMetricSelected()) {
            $tester = $this->tester;
            $tester->click($this->metricBtn);
            $this->weightUnits = 'kg';
            $this->measurementUnits = 'metric';
            $this->initElements();
        }
        return $this;
    }

    /**
     * Choose imperial units. The form's elements are reinitialized as the units play a role in the selectors.
     */
    public function chooseImperialUnits()
    {
        if($this->isMetricSelected()) {
            $tester = $this->tester;
            $tester->click($this->imperialBtn);
            $this->weightUnits = 'lb';
            $this->measurementUnits = 'imperial';
            $this->initElements();
        }
        return $this;
    }

    /**
     * Checks if the current selected measurement system is metric.
     * @return bool true if metric is selected, false otherwise
     */
    public function isMetricSelected()
    {
        return $this->tester->grabMultiple($this->metricSelected) > 0;
    }

    /**
     * Fill all the fields in the measurements form with the given numbers
     * @param $age float age
     * @param $height float height
     * @param $weight float weight
     * @param $targetWeight float target weight
     * @return $this
     */
    public function fillMetricFields($age, $height, $weight, $targetWeight)
    {
        $tester = $this->tester;
        $tester->fillField($this->ageField, $age);
        $tester->fillField($this->heightCmField, $height);
        $tester->fillField($this->weightField, $weight);
        $tester->fillField($this->targetWeightField, $targetWeight);
        return $this;
    }

    /**
     * Builds an error string for value greater or equal to the given number.
     * @param $number int the number to be used in the error message
     * @return string the built error message
     */
    public function getGreaterOrEqualErrorMsg($number)
    {
        return $this->greaterOrEqualMsg . $number . ".";
    }

    /**
     * Builds an error string for value less or equal to the given number.
     * @param $number int the number to be used in the error message
     * @return string the built error message
     */
    public function getLessOrEqualErrorMsg($number)
    {
        return $this->lessOrEqualMsg . $number . ".";
    }

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL . $param;
    }

}
