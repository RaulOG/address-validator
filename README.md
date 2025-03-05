# PHP Engineer panel assignment

**Congratulations on making it to the panel stage of your recruiting process at
FinDock.**

Even more importantly, thank you for your interest and taking the time! The goal of the panel interview process is to see if you feel comfortable in the development process at FinDock. We encourage you to be creative!

This is the most important step of the recruitment process in which you will present to a group of senior FinDock members. Expect a very interactive session in which you will be asked a lot of questions.

## General instructions
- We expect you to spend about 4-5 hours on all exercises combined. Please try to
limit yourself to this timebox. You won't be judged on perfection, we just want to get a feel on how you will perform once you are part of FinDock.

- Please stop on time and document the things you would've done differently or
better if you had more time so that we're not left wondering if you made decisions
because you didn't know better or because you had no more time.

- If you have questions about the panel then it's more than fine to ask questions by
sending an e-mail to jorrit@findock.com. Asking questions is even welcomed, as
this is definitely also expected after you joined FinDock.

- You can use any technology or format for delivering your presentation. You will not be judged on your presentation skills, so please don't spend too much time on this.

- Please start your presentation with a personal introduction of yourself.

- It would be really nice for us to see how you approached the exercises. Once you're happy with your changes and everything is committed to this repository, please share the repository with us so we can prepare the panel beforehand.

## Excercises

1. The repository contains an application that allows a user to login and perform address validation via an external service. The current codebase is not as clean as it can be. It's missing certain elements that are to be expected. On top of that there's room for improvements and best practices. An important part of working at FinDock is performing code reviews to make sure our newly created product is as good as it can be. Please perform and present your code review on the current code base to your style, liking and standards.

2. Currently the validation results are not returned to the user in a meaningful way. Can you update the application to return the validation results to the user in a meaningful way?

3. Write code that does the same validation as the current, but with a csv that contains at least 1000 addresses. Make the code as performant as possible. Demo and proof the performance during the presentation live.

4. Every product requires a lot of (unit) testing but at FinDock we find it important
to validate our code, processes and integrations via unit-, integration- and manual tests. How would you approach testing this code? We don't expect 100% test coverage but it would be good to see skeleton code with comments on the outline of other test cases and test setup.

5. Imagine that we want to offer different Address Validation Providers (e.g. different API implementations). How would you approach and architect this? This should be pseudo code and doesn't have to be working code! Please include this as part of your presentation during the panel.

6. Let's imagine our functionality is well received and we would like to make a real product out of it. What changes and requirements can you imagine that would be required to make it a product that adds value to the hypothetical customer? Please note that this is not needed in actual code but e.g. listed on a set of slides.


### Used Api

The panel assignment repository uses geoapify.com as a provider for address validation. You can sign up for free account at their [website](https://www.geoapify.com/)