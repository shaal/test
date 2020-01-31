@api
Feature: Find a doctor
  As a TH Consumer,
  I want to be able to search for a doctor,
  so that I can learn about them and make an appointment.

  Scenario: Verify that find a doctor exists
    Given I am not logged in
    When I am at "/doctors"
    And I fill in "find_doctor_search" with "John Doe"
    Then I am at "doctors"
