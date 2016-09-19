<?php

class RealStateTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testindex()
	{
		$response = $this->call('GET', '/backend/realstate');

		$this->assertEquals(200, $response->getStatusCode());
	}

}
