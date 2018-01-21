<?php

use \AcceptanceTester as AT;

/**
 * A test file testing every field's validation in the measurements form
 */
class MeasurementsFormCest
{

    /**
     * Going to the measurements form.
     * @param AcceptanceTester $I
     */
    public function _before(AT $I)
    {
        $basePO = new Page\BasePageObject($I);
        $I->am('user');
        $I->wantTo('go to the measurements page');
        $I->lookForwardTo('test the metric/imperial forms fields in the measurements page');
        $I->amOnPage('/');
        $I->click('.js-gender-man');
        //No good selector ... sorry
        $I->click('.dietAnswer[data-value="146"]');
        $I->click($basePO->continueBtn);
        $I->click($basePO->continueBtn);
        $I->click($basePO->continueBtn);
        $I->click($basePO->continueBtn);
        $I->click('.dietAnswer[data-value="141"]');
        $I->click($basePO->continueBtn);
    }

    /**
     * Tests each individual field (imperial+metric) for invalid values, valid values, over/under the acceptable range, negative values, required/not required fields.
     * @param AcceptanceTester $I
     * @throws Exception
     * @throws \Codeception\Exception\ElementNotFound
     */
    public function testEachIndividualFieldErrors(AT $I)
    {
        $measurementsPage = new Page\MeasurementsPageObject($I, 'metric');
        $I->wantTo('choose metric units');
        $measurementsPage->chooseMetricUnits();

        $I->wantToTest('if the age field\'s validations are working');
        $this->testMeasurementField($I, $measurementsPage->ageField, 14, 99, true, $measurementsPage);
        $I->wantToTest('if the height field\'s validations are working');
        $this->testMeasurementField($I, $measurementsPage->heightCmField, 135, 255, true, $measurementsPage);
        $I->wantToTest('if the weight field\'s validations are working');
        $this->testMeasurementField($I, $measurementsPage->weightField, 40, 180, true, $measurementsPage);
        $I->wantToTest('if the target weight field\'s validations are working');
        $this->testMeasurementField($I, $measurementsPage->targetWeightField, 40, 180, true, $measurementsPage);

        $I->wantTo('choose imperial units');
        $measurementsPage->chooseImperialUnits();

        //The test will fail in this step because there's a bug in this field.
        $I->wantToTest('if the age field\'s validations are working');
        $this->testMeasurementField($I, $measurementsPage->ageField, 14, 99, true, $measurementsPage);
        $I->wantToTest('if the height (ft) field\'s validations are working');
        $this->testMeasurementField($I, $measurementsPage->heightFtField, 4, 8, true, $measurementsPage);
        $I->wantToTest('if the height (inch) field\'s validations are working');
        $this->testMeasurementField($I, $measurementsPage->heightInchField, 0, 11, false, $measurementsPage);
        $I->wantToTest('if the weight field\'s validations are working');
        $this->testMeasurementField($I, $measurementsPage->weightField, 90, 400, true, $measurementsPage);
        $I->wantToTest('if the target weight field\'s validations are working');
        $this->testMeasurementField($I, $measurementsPage->targetWeightField, 90, 400, true, $measurementsPage);
    }


    /**
     * Tests one field in the imperial/metric measurements form for valid/invalid/text values, values under/over the acceptable range, negative values, required/not required.
     * As this is a private function, it is not considered as a separate test. It could be placed in some common steps class.
     * @param AcceptanceTester $I
     * @param $testedField string selector
     * @param $minValidValue float minimum valid value for the tested field
     * @param $maxValidValue float maximum valid value for the tested field
     * @param $fieldRequired bool indicator if the field is required or not
     * @param \Page\MeasurementsPageObject $measurementsPage measurements page PageObject instance
     * @throws Exception
     * @throws \Codeception\Exception\ElementNotFound
     */
    private function testMeasurementField(AT $I, $testedField, $minValidValue, $maxValidValue, $fieldRequired, \Page\MeasurementsPageObject $measurementsPage): void
    {

        $I->cantSee($measurementsPage->errorTooltipElements);
        $I->cantSee($measurementsPage->fieldsMarkedAsErrorElements);

        //I could get the test and validation numbers from a file or some kind of key->value table
        $I->fillField($testedField, $minValidValue - 1);
        $I->pressKey($testedField, WebDriverKeys::TAB);
        $I->waitForText($measurementsPage->getGreaterOrEqualErrorMsg($minValidValue, 1));
        $I->see($measurementsPage->getGreaterOrEqualErrorMsg($minValidValue));

        $I->fillField($testedField, -5);
        $I->pressKey($testedField, WebDriverKeys::TAB);
        $I->waitForText($measurementsPage->getGreaterOrEqualErrorMsg($minValidValue, 1));
        $I->see($measurementsPage->getGreaterOrEqualErrorMsg($minValidValue));

        $I->fillField($testedField, $maxValidValue + 1);
        $I->pressKey($testedField, WebDriverKeys::TAB);
        $I->waitForText($measurementsPage->getLessOrEqualErrorMsg($maxValidValue, 1));
        $I->see($measurementsPage->getLessOrEqualErrorMsg($maxValidValue));

        $I->fillField($testedField, "ee");
        $I->pressKey($testedField, WebDriverKeys::TAB);
        $I->waitForText($measurementsPage->notANumberErrorMsg);
        $I->see($measurementsPage->notANumberErrorMsg);

        if ($fieldRequired) {
            $I->fillField($testedField, "");
            $I->pressKey($testedField, WebDriverKeys::TAB);
            $I->waitForText($measurementsPage->fieldRequiredErrorMsg);
            $I->see($measurementsPage->fieldRequiredErrorMsg);
        }

        $I->fillField($testedField, $maxValidValue);
        $I->pressKey($testedField, WebDriverKeys::TAB);
        $I->waitForElementNotVisible($measurementsPage->errorTooltipElements, 1);
        $I->cantSee($measurementsPage->fieldsMarkedAsErrorElements);
        $I->cantSee($measurementsPage->errorTooltipElements);

        $I->fillField($testedField, $minValidValue);
        $I->pressKey($testedField, WebDriverKeys::TAB);
        $I->waitForElementNotVisible($measurementsPage->errorTooltipElements, 1);
        $I->cantSee($measurementsPage->fieldsMarkedAsErrorElements);
        $I->cantSee($measurementsPage->errorTooltipElements);
    }

}

?>