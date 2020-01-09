@api @d8
Feature: CTA Banner Paragraph
  As a TH Editor,
  I want to add a CTA Banner component.
  So I can see said component with the correct data.

  Scenario: Verify that paragraph fields are present
    Given I am logged in as a user with the "administrator" role
    When I visit "/admin/structure/paragraphs_type/cta_banner/fields"
    Then I should see the text "Image"
    Then I should see the text "Subtitle"
    Then I should see the text "Title (top)"
    Then I should see the text "Title (bottom)"
    Then I should see the text "Link"
