##### Completeness
* I believe mappings is meant to be a form field which allows to map csv header values to custom values. Change frontend to allow for each csv header value to be specified a custom value. 
* Handle ProcessCsvUploadJobs that fail because sending a batch job to geoapify fails.
##### Code Quality
* Stop passing variables by reference to increase readability of code flow
* Replace hardcoded url value in Dashboard vue component with Laravel`s route name for flexibility
##### Optimization
* I believe the main optimization point of the ProcessCsvUploadJob is the one I have addressed; Transforming 1 request to Geoapify for each csv row to a batch Geoapify request. That does not mean there is not a lot yet to be optimized.
* Investigate if its better using the native storage from Laravel to access the uploaded csv instead of using native php file function
* Investigate if we can read the csv differently (pointers)
* Due to the nature of addresses, I would think we can reuse Geoapify responses among different csv uploads. Currently, addresses validated with Geoapify are only reused within a csv upload.
##### Testing
* I believe unit tests are tests that are meant to test classes in isolation. A unit test shouldn't ever create data in db since that would break the isolation and therefore make the test an integration or feature test.
* I believe unit tests should be created once the application is at a stage we consider robust. In the current scenario, I would still refactor the code so that I wouldn't yet add unit tests.
* Add datasets in Feature/CsvUploadControllerTest to test thoroughly both validation and invalidation scenarios
* Add tests to assert for jobs (ProcessCsvUploadJob and ValidateGeocodeJob) perform as expected
#### Address Validation Providers
* In case we would want to have different address validation providers, it would depend a lot on how we would want to choose which provider to use when, but the main idea for the application is to create an interface with methods that each provider has to adhere to, and be sure to inject the specific provider implementation to the classes that require the service.
#### Extra
* If the functionality was well received, there are a number of steps that could be done
    * Ensure the application can handle csv files bigger than 1000 addresses (geoapify batch limit is 1k), most likely by creating several batch jobs for each csv upload.
    * Double check functionality handles well all exception scenarios such as non responding external API. Currently, some jobs could end up failing or not created. 
    * Prettyfy frontend
