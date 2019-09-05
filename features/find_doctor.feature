@api
Feature: Find a doctor
  As a TH Consumer,
  I want to be able to search for a doctor,
  so that I can learn about them and make an appointment.

  Scenario: Verify that find a doctor exists
    Given I am not logged in
    When I run cron
    When I am at "/"
    And I fill in "find_doctor_search" with "John Doe"
    And I press "edit-submit-find-a-provider"
    Then I should see the text "John Doe"
    And I should not see the text "Jane Doe"

  Scenario: Verify that find a doctor searches by specialty
    Given I am not logged in
    When I am at "/"
    And I fill in "find_doctor_search" with "Pediatric Neurology"
    And I press "edit-submit-find-a-provider"
    Then I should see the text "Jane Doe"
    And I should not see the text "John Doe"

  Scenario: Verify that find a doctor searches by condition
    Given I am not logged in
    When I am at "/"
    And I fill in "find_doctor_search" with "Pink Eye"
    And I press "edit-submit-find-a-provider"
    Then I should see the text "John Doe"
    And I should not see the text "Jane Doe"
