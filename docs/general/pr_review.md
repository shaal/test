# PR Review Guidelines

Pull Request (PR) Review is one of the most important activities you are involved in. As an individual, PRs are a medium for developing your communication and programming skills, getting feedback from your team, and teaching and being taught by others. You learn dialectic, best practices, design patterns, new ways to solve problems, and how to write good code. As a team on a project, they are a medium for sharing knowledge, establishing common terminology, discussing architectural decisions, ensuring the quality of code, and collaborative problem solving. As a client of Palantir, they are a medium for ensuring that that what is being built meets the user story acceptance criteria.

Written below are guidelines on how to do PR Review as well as pointers on what makes a good PR Review.

## Table of Contents

* [PR Review Process](#pr-review-process)
* [Giving PR Feedback](#giving-pr-feedback)
    * [Clearly explain why you are offering the feedback.](#clearly-explain-why-you-are-offering-the-feedback)
    * [Ask for clarification. Explain why you are asking.](#ask-for-clarification-explain-why-you-are-asking)
    * [Offer solutions (or resources).](#offer-solutions-or-resources)
    * [Resolve feedback.](#resolve-feedback)
    * [Resources and further reading](#resources-and-further-reading)
* [PR Review Values](#pr-review-values)
    * [Objectivity](#objectivity)
    * [Empathy](#empathy)
    * [Comprehensibility](#comprehensibility)
    * [Quality](#quality)
    * [Stewardship](#stewardship)

## PR Review Process

Here is a general guideline of the steps to take for PR Review:

1. Assign the PR to yourself to make it clear you are reviewing the PR.
1. Make sure the branch follows our [Git workflow](/git/workflow.md) and [Git commit guidelines](/git/commit_guidelines.md).
1. Review the [CONTRIBUTING](https://help.github.com/articles/setting-guidelines-for-repository-contributors/) guidelines of the repository and make sure the PR follows them.
1. Review the ticket for the submitted code and verify it meets the acceptance criteria.
1. Complete the testing instructions mentioned in the PR and/or ticket to make sure it passes.
1. Read the [PR diff](https://help.github.com/articles/using-pull-requests/#reviewing-proposed-changes) to:
    1. Make sure the code follows standards and best practices as outlined in the development documentation.
    1. Make sure the code does not have unintentional or out of scope changes.
    1. If you have a question, comment, objection, or concern about a line of code, note it in a [comment](https://help.github.com/articles/commenting-on-the-diff-of-a-pull-request/) on the line of code in the PR that it affects or should be changed.
1. Make sure that the code follows the project's deployment plan.
1. Assuming the branch passes review:
    1. [merge](https://help.github.com/articles/merging-a-pull-request/) the branch.
    1. [delete](https://help.github.com/articles/deleting-unused-branches/) the branch.
    1. update the ticket to note it has been merged.

## Giving PR Feedback

There are some simple things you can do when offering PR feedback that will help increase clarity and make responding to your feedback easier.

### Clearly explain why you are offering the feedback.

Use the following specific keywords to indicate what the submitter’s next course of action should be:

* *Fix*: when the code you are commenting on absolutely must change before you are comfortable merging the code (e.g.: “Fix this conditional block to include xyz…”)
* *Suggest*: when you think a change is warranted but will still merge the PR if the submitter is unable to make the change. (e.g.: "I suggest you avoid doing...")
* *Update*: When the code introduces a change that needs to be documented elsewhere (e.g.: “Update the README file”).
* *Explain*: When the code needs more inline comments or documentation. (e.g.: "Please explain why this is necessary.")

*Note*: If there are multiple instances of similar changes needed, it is not rude to reference a single comment multiple times. For example: “Same as [link to comment](#).”

You should also offer feedback that doesn’t require a next-action from the submitter--such as learning resources and praise. For example:

* “++ Yes!”
* “This is a really good solution because…”
* “You may want to familiarize yourself with PSR-4 autoloading. See this [blog post](#) and our [internal documentation here](#).”

### Ask for clarification. Explain why you are asking.

If there is a block of code that does seem obviously correct, but you’re unsure of what the next action should be, you should ask questions. When asking a question, make sure you include some explanation as to why you are asking the question.

*Bad*: “Is PUT allowed here?”

*Good*: “Is PUT allowed here? If it is, I’d like to see a test for it in the test suite.”

*Bad*: “Why are you doing this?”

*Good*: “Why does this code exclude XYZ? I think it can be included if you re-export the feature.”

### Offer solutions (or resources).

When asking for changes to code, remember that the submitter does not have the same knowledge base that you do. If there is a specific solution that you’d like to see implemented, offer that advice or links to resources.

*Bad*: “Please refactor.”

*Good*: “Fix the indentation, please; it doesn’t comply with the style guide. See link.”

### Resolve feedback.

Don’t leave feedback unresolved. If you’ve asked for clarification or an explanation and the submitter responded, acknowledge their response. For example:

> You: “Is PUT allowed here? If it is, I’d like to see a test for it in the test suite.”

> Them: “We could add a test for it, but it is explicitly configured to not allow PUT.”

> You: “Got it. I don’t think we need tests given the configuration.”

#### Resources and further reading

* [How to write the perfect pull request](https://github.com/blog/1943-how-to-write-the-perfect-pull-request)
* [Code Review](https://github.com/thoughtbot/guides/tree/master/code-review)

## PR Review Values

Palantir's core values manifest themselves in all areas of our work. Here are examples of how some of them apply to pull requests. For any given pull request it is possible that some of these values may appear to be in conflict with one another.

Here are values to uphold during PR review:

### Objectivity

The goal of almost all pull requests is to solve a project need in the best way possible given the project's circumstances. A degree of objectivity helps the reviewer evaluate whether the pull request meets the need well.

* Contributions made by the reviewer to the pull request (actual commits as well as pre-pull request conversations) reduce the reviewer's objectivity.
* Merging your own code should be avoided.


### Empathy

Review others' pull requests as you would have them review yours.

On the surface level pull requests deal in code. Additionally pull requests are an opportunity to cultivate the skills of our co-workers. A healthy team separates heavy critique of code from personal commentary. When making comments on a pull request ask yourself whether a code defect may reflect a gap in the author's knowledge. If so, a conversation with the author may be more constructive than a line-by-line critique.

* Make apparent the *why* of your suggested change in addition to *what* and *how*.
* Celebrate good code. If you are impressed, pleased, or learned something by reviewing another person's code, make a comment in the PR and tell your colleagues about the great work in an appropriate way.


### Comprehensibility

Pull requests should be understandable. To that end:

 * Lack of documentation is a critical bug. Make sure that you can understand the intent and behavior of the code.
 * Pull request authors should given relevant instruction to the reviewer.
 * Point out comments that do not sufficiently explain *why* code is present (often poor comments will try to explain *what* code is rather than *why* it is there).
 * "Review code as though you’ve never seen the system it exists in." (Source: [Medium](https://medium.com/@landongn/12-years-later-what-i-ve-learned-about-being-a-software-engineer-d6e334d6e8a3))
 * Reading a test in a pull request should tell the review what the code does.

Feedback on pull requests must be comprehensible as well. It is often the method by which team members learn best practices and standards.

* If you have a question, comment, objection, or concern about a line of code, note it in a [comment](https://help.github.com/articles/commenting-on-the-diff-of-a-pull-request/) on the line of code in the PR that it affects or should be changed.
* If you have a question, comment, objection, or concern about the code in general, note it in a comment on the PR.

### Quality

Quality code is important to the maintainability and extensibility of a system. Some quality code checks that should happen as part of a PR Review are:

* Incorrect, incomplete, poorly architected, or incompletely documented code should not be merged. It is your responsibility to point out [code mess](http://phpmd.org/), [dead code](https://github.com/sebastianbergmann/phpdcd), violations of standards or best practices, performance concerns, security concerns, and cases where a design pattern could be used to improve quality.
* Incorrect documentation is a critical bug. Make sure that changes that are made are also reflected in relevant documentation.
* Untested code should not be merged. Assign the PR back to the owner if:
    * You cannot test the code.
    * Testing instructions are missing.
    * There are no acceptance criteria on the story.
* Tests should validate business requirements. If a test is not useful or does not add value to the project, it should not be merged.

### Stewardship

We are stewards of our clients' resources: time, budget and focus.

* Some pull requests require a close line-by-line reading and review. Others require less code review and more hands-on functionality testing. Be clear with your team what is to be expected for any given pull request.
* Be clear about how quality standards vary within and across projects. If one portion a project's code is meant for public release or meant as a baseline API to be used all over the project then it demands more time and effort to document and vet than other portions of the project.
* Where appropriate, document architectural, performance, or technical decisions and concerns that are relevant for maintenance and extensibility.
* As a steward, it is your responsibility to help the project succeed. If a pull request is incomplete in helping ensure project success, assign it back to the submitter with suggestions of how to improve it. Ultimately, our projects succeed when:
    * Our clients' business objectives have been met.
    * Our code runs successfully in a production environment.
    * The client can take full ownership of the system after the project's completion.