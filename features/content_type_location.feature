@api
Feature: Location Content Type
  As a TH Consumer,
  I want to be able to read information about a location,
  so that I can find out where the location is and when it is open.

  Scenario: Verify that location fields are present
    Given I am logged in as a user with the "administrator" role
    When I visit "/admin/structure/types/manage/location/fields"
    Then I should see the text "Address"
    Then I should see the text "Components"
    Then I should see the text "Description"
    Then I should see the text "Fax number"
    Then I should see the text "Hours of operation"
    Then I should see the text "How to find us"
    Then I should see the text "Image"
    Then I should see the text "Insurance Information"
    Then I should see the text "Location ID"
    Then I should see the text "Location Type"
    Then I should see the text "Phone number"

  Scenario: Verify that migrated location fields are read only
    Given I am logged in as a user with the "administrator" role
    When I visit "node/add/location"
    Then I should see the text "Address"
    Then I should see the text "Components"
    Then I should see the text "Description"
    Then I should not see the text "Fax number"
    Then I should not see the text "Hours of operation"
    Then I should see the text "How to find us"
    Then I should see the text "Image"
    Then I should see the text "Insurance Information"
    Then I should not see the text "Location ID"
    Then I should see the text "Location Type"
    Then I should see the text "Phone number"
