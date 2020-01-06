@api @d8
Feature: Homepage Hero Paragraph
  As a TH Editor,
  I want to add a Homepage Hero component.
  So I can see said component with the correct data.

  Scenario: Verify that paragraph fields are present
    Given I am logged in as a user with the "administrator" role
    When I visit "/admin/structure/paragraphs_type/homepage_hero/fields"
    Then I should see the text "Image"
    Then I should see the text "Subtitle"
    Then I should see the text "Title (first)"
    Then I should see the text "Title (last)"
    Then I should see the text "Title (middle)"
