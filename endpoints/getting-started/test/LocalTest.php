<?php
/**
 * Copyright 2016 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\RequestFactory;

class LocalTest extends TestCase
{
    public function testEcho()
    {
        $app = require __DIR__ . '/../app.php';

        $message = <<<EOF
So if you're lost and on your own
You can never surrender
And if your path won't lead you home
You can never surrender
EOF;

        // create and send in JSON request
        $request = (new RequestFactory)->createRequest('POST', '/echo')
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(json_encode([
            'message' => $message
        ]));

        $response = $app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode((string) $response->getBody(), true);
        $this->assertNotNull($json);
        $this->assertArrayHasKey('message', $json);
        $this->assertEquals($message, $json['message']);
    }
}
