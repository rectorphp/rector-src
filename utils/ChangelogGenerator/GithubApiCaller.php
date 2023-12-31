<?php

declare(strict_types=1);

namespace Rector\Utils\ChangelogGenerator;

use Httpful\Request;
use Httpful\Response;
use InvalidArgumentException;
use Rector\Utils\ChangelogGenerator\Enum\RepositoryName;
use Rector\Utils\ChangelogGenerator\Exception\GithubRequestException;
use Rector\Utils\ChangelogGenerator\ValueObject\Commit;
use stdClass;

final class GithubApiCaller
{
    public function __construct(
        private readonly string|false $githubToken
    ) {
        if ($githubToken === false) {
            throw new InvalidArgumentException(
                'Provide GitHub token via: "GITHUB_TOKEN=*** bin/generate-changelog.php ..."'
            );
        }
    }

    public function searchIssues(Commit $commit): stdClass
    {
        $requestUri = sprintf(
            'https://api.github.com/search/issues?q=repo:%s+%s+is:issue',
            RepositoryName::DEPLOY,
            $commit->getHash()
        );

        return $this->sendRequest($requestUri);
    }

    public function searchPullRequests(Commit $commit): stdClass
    {
        $requestUri = sprintf(
            'https://api.github.com/search/issues?q=repo:%s+%s+is:pull-request',
            RepositoryName::DEVELOPMENT,
            $commit->getHash()
        );

        return $this->sendRequest($requestUri);
    }

    private function sendRequest(string $requestUri): stdClass
    {
        /** @var Response $response */
        $response = Request::get($requestUri)
            ->sendsAndExpectsType('application/json')
            ->basicAuth('tomasvotruba', $this->githubToken)
            ->send();

        if ($response->code !== 200) {
            throw new GithubRequestException($response->body->message, (int) $response->code);
        }

        return $response->body;
    }
}
