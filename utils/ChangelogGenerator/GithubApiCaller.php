<?php

declare(strict_types=1);

namespace Rector\Utils\ChangelogGenerator;

use Httpful\Request;
use Httpful\Response;
use Rector\Utils\ChangelogGenerator\Exception\GithubRequestException;
use Rector\Utils\ChangelogGenerator\ValueObject\Commit;

final class GithubApiCaller
{
    public function __construct(
        private readonly string $githubToken
    ) {
    }

    public function searchIssues(Commit $commit): \stdClass
    {
        $requestUri = sprintf(
            'https://api.github.com/search/issues?q=repo:%s+%s',
            Enum\RepositoryName::DEPLOY,
            $commit->getHash()
        );

        return $this->sendRequest($requestUri);
    }

    public function searchPullRequests(Commit $commit): \stdClass
    {
        $requestUri = sprintf(
            'https://api.github.com/search/issues?q=repo:%s+%s',
            Enum\RepositoryName::DEVELOPMENT,
            $commit->getHash()
        );

        return $this->sendRequest($requestUri);
    }

    private function sendRequest(string $requestUri): \stdClass
    {
        /** @var Response $response */
        $response = Request::get($requestUri)
            ->sendsAndExpectsType('application/json')
            ->basicAuth('tomasvotruba', $this->githubToken)
            ->send();

        if ($response->code !== 200) {
            throw new GithubRequestException(get_debug_type($response->body), (int) $response->code);
        }

        return $response->body;
    }
}
