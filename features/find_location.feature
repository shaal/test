@api
Feature: Find a doctor
  As a TH Consumer,
  I want to be able to search for a location,
  so that I can find a location that has the services I need and how to get directions.

  Scenario: Verify that find a doctor exists
    Given I am not logged in
    When I am at "/find-a-location"
    And I fill in "find_location_search" with "Reading Hospital"
    Then I am at "find-a-location"
