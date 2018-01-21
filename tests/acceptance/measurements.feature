Feature: fillMeasurements
  In order to generate a good diet for my body
  As a user
  I need to fill my measurements in the measurements page

  Background:
    Given I've cleaned all saved data
  Scenario Outline: Try to generate a diet with different measurements values in metric system
    Given I am on the measurements page
    When I choose "metric" measurements system
    And I fill "<age>" in the "age" field
    And I fill "<height>" in the "height" field
    And I fill "<weight>" in the "weight" field
    And I fill "<targetWeight>" in the "target weight" field
    Then I "<canContinue>" continue to the diet generation page
    Examples:
      | age   | height    | weight   | targetWeight | canContinue |
      | +14   | +165      | +65      | +75          | can         |
      | -14   | +165      | +65      | +75          | can't       |
      | 14    | 155.34323 | 65.23243 | 89.954858    | can         |
      | ee    | ee        | ee       | 65           | can't       |
    #I could load this data from a file and even load the selectors from a file, instead of using an "enum"