@api
Feature: Location Content Type
  As a TH Consumer,
  I want to be able to read information about a provider,
  so that I can find out what if I should make an appointment with them.

  Scenario: Verify that provider fields are present
    Given I am logged in as a user with the "administrator" role
    When I visit "/admin/structure/types/manage/profile/fields"
    Then I should see the text "Hospital Affiliation"
    Then I should see the text "Clinical Area of Focus"
    Then I should see the text "Board Certified"
    Then I should see the text "Comment Count"
    Then I should see the text "Comments"
    Then I should see the text "Education"
    Then I should see the text "First Name"
    Then I should see the text "Gender"
    Then I should see the text "Image"
    Then I should see the text "Insurance accepted"
    Then I should see the text "Job Title"
    Then I should see the text "Languages"
    Then I should see the text "Last Name"
    Then I should see the text "Location"
    Then I should see the text "Medical Specialties"
    Then I should see the text "Middle Name"
    Then I should see the text "NPI ID"
    Then I should see the text "Open Scheduling Available"
    Then I should see the text "Overall Rating"
    Then I should see the text "Phone Number"
    Then I should see the text "Professional Title"
    Then I should see the text "Provider Type"
    Then I should see the text "Rating Count"
    Then I should see the text "Video"

  Scenario: Verify that migrated provider fields are read only
    Given I am logged in as a user with the "administrator" role
    When I visit "node/add/profile"
    Then I should not see the text "Affiliated Hospitals"
    Then I should not see the text "Areas of Interest"
    Then I should not see the text "Board Certified"
    Then I should not see the text "Board Eligible"
    Then I should not see the text "Comment Count"
    Then I should not see the text "Comment Count"
    Then I should not see the text "Gender"
    Then I should not see the text "Insurance accepted"
    Then I should not see the text "Job Title"
    Then I should not see the text "Languages"
    Then I should not see the text "Overall Rating"
    Then I should not see the text "Phone Number"
    Then I should not see the text "Professional Title"
    Then I should not see the text "Provider Type"
    Then I should not see the text "Rating count"