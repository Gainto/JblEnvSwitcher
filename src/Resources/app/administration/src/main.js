import './app/component/base/sw-version';

import EnvSwitcherApiService from './core/service/api/env-switcher.api.service';

Shopware.Service().register('envSwitcherApiService', (container) => {
    const initContainer = Shopware.Application.getContainer('init');
    return new EnvSwitcherApiService(
        initContainer.httpClient,
        container.loginService
    );
});
