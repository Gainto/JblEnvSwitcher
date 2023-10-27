const { ApiService } = Shopware.Classes;

/**
 * @class
 * @extends ApiService
 */
class EnvSwitcherApiService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'switch-environment') {
        super(httpClient, loginService, apiEndpoint);
        this.name = 'envSwitcherApiService';
    }

    switchEnvironment(targetEnvironment, additionalParams = {}, additionalHeaders = {}) {
        let route = `/_action/switch-environment`;
        const headers = this.getBasicHeaders(additionalHeaders);

        const params = {
            targetEnvironment
        };

        return this.httpClient
            .post(route, params, {
                additionalParams,
                headers
            }).then((response) => {
                return ApiService.handleResponse(response);
            });
    }

    getCurrentEnvironment(additionalParams = {}, additionalHeaders = {}) {
        let route = `/_action/current-environment`;
        const headers = this.getBasicHeaders(additionalHeaders);

        const params = {};

        return this.httpClient
            .get(route, params, {
                additionalParams,
                headers
            }).then((response) => {
                return ApiService.handleResponse(response);
            });
    }
}

export default EnvSwitcherApiService;
