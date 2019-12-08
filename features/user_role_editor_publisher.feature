@api @user
Feature: User Roles
  As an Editor,
  I want to be able to edit existing pages within my section of the site,
  So that I can update the content that I have.

  Scenario: Verify that I cannot create a new page
    Given I am logged in as a user with the "publisher" role
    When I go to "node/add/page"
    Then I should get a "403" HTTP response

  Scenario: Verify that I can edit an existing page
    Given I am logged in as a user with the "publisher" role
    And I am viewing a "page" with the title "Publisher test"
    When I click "edit"
    Then I should get a "200" HTTP response
    And I should see the text "Edit Page Publisher Test"

  Scenario: Verify that I cannot create a new location
    Given I am logged in as a user with the "publisher" role
    When I go to "node/add/location"
    Then I should get a "403" HTTP response

  Scenario: Verify that I cannot create a new profile
    Given I am logged in as a user with the "publisher" role
    When I go to "node/add/profile"
    Then I should get a "403" HTTP response

  Scenario: Verify that I cannot create a new service
    Given I am logged in as a user with the "publisher" role
    When I go to "node/add/service"
    Then I should get a "403" HTTP response

  Scenario: Verify that I cannot create a news article
    Given I am logged in as a user with the "publisher" role
    When I go to "node/add/article"
    Then I should get a "403" HTTP response
