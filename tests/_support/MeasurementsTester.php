<?php
/**
 * Steps used for the measurements page
 * Created by PhpStorm.
 * User: feste
 * Date: 20-Jan-18
 * Time: 18:26
 */

class MeasurementsTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * I don't want to restart the browser in order to clean the local storage.
     * There may be another way but I couldn't find it
     * @Given I've cleaned all saved data
     */
    public function cleanSavedData()
    {
        $this->executeJS("localStorage.clear();");
    }

    /**
     * A step that just goes to the measurements page without fillin any data.
     * In a real world situation those selectors would be in a separate PageObject and there will be functions
     * to go between the screens in a separate class with more common steps inside.
     * @Given I am on the measurements page
     */
    public function iAmOnTheMeasurementsPage()
    {
        $this->am('user');
        $this->wantTo('go to the measurements page');
        $this->lookForwardTo('test the metric/imperial forms in the measurements page');
        $this->amOnPage('/');
        $this->saveSessionSnapshot("cleanSessionSnapshot");
        $this->click('.js-gender-man');
        $this->click('.dietAnswer[data-value="146"]');
        $this->click('.m-test-checkboxes__continue button');
        $this->click('.m-test-checkboxes__continue button');
        $this->click('.m-test-checkboxes__continue button');
        $this->click('.m-test-checkboxes__continue button');
        $this->click('.dietAnswer[data-value="141"]');
        $this->click('.m-test-checkboxes__continue button');
    }

    /**
     * Switch the measurement system to metric or imperial
     * @When I choose ":measurementSystem" measurements system
     * @param $measurementSystem - string, "imperial" or "metric"
     */
    public function iChooseMeasurementsSystem($measurementSystem)
    {
        $measurementsPage = new \Page\MeasurementsPageObject($this, 'metric');
        switch ($measurementSystem) {
            case "metric":
                $measurementsPage->chooseMetricUnits();
                break;
            case "imperial":
                $measurementsPage->chooseImperialUnits();
                break;
        }
    }

    /**
     * Fill a field with some value. In this case it's for the fields in the measurements form, but it
     * could be modified for more wide range usage with an external key->value table
     * @When I fill ":value" in the ":field" field
     * @param $value value to fill in the selected field
     * @param $field field field identifier, in this case from an enum: [age, height, weight, target weight, height ft, height inch]
     */
    public function iFillInTheField($value, $field)
    {
        $measurementsPage = new \Page\MeasurementsPageObject($this, 'metric');
        $fieldToFill = null;
        switch ($field) {
            case 'age':
                $fieldToFill = $measurementsPage->ageField;
                break;
            case 'height':
                $fieldToFill = $measurementsPage->heightCmField;
                break;
            case 'height ft':
                $fieldToFill = $measurementsPage->heightFtField;
                break;
            case 'height inch':
                $fieldToFill = $measurementsPage->heightInchField;
                break;
            case 'weight':
                $fieldToFill = $measurementsPage->weightField;
                break;
            case 'target weight':
                $fieldToFill = $measurementsPage->targetWeightField;
                break;
        }
        $this->fillField($fieldToFill, $value);

    }

    /**
     * Tests if the user can advance to the diet generation page. The assertion for errors is the visibility of any error tooltip in the measurements page.
     * @Then I ":canContinue" continue to the diet generation page
     */
    public function canContinueToGenerationPage($canContinue)
    {
        $measurementsPage = new \Page\MeasurementsPageObject($this, 'metric');
        if ($canContinue == 'can') {
            $this->click($measurementsPage->continueBtn);
            //This element would usually go in another class (page object) in the real world
            $this->waitForElementVisible(".a-checkbox__label", 2);
        } else {
            $this->click($measurementsPage->continueBtn);
            //This element would usually go in another class (page object) in the real world
            $this->waitForElementVisible($measurementsPage->errorTooltipElements, 1);
        }
    }

    /**
     * Clicks the continue button
     * @When I click Continue
     */
    public function iClickContinue()
    {
        $measurementsPage = new \Page\MeasurementsPageObject($this, 'metric');
        $this->click($measurementsPage->continueBtn);
    }


}