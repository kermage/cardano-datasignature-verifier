<?php

/**
 * @package ThemePlate
 */

namespace CardanoDataSignature\Addresses;

use CardanoDataSignature\Utilities\Network;

use function BitWasp\Bech32\convertBits;
use function BitWasp\Bech32\encode;

abstract class AbstractAddress {
    private $addressHex = "";
    private $addressBytes = "";
    private $addressBech32 = "";
    protected Network $network;

	public const DATA = '';

    public function __construct(Network $network) {
        $this->network = $network;
    }

    protected function computeBech32($addressBytes) {
		$unpack = unpack("C*", $addressBytes);
        $words = convertBits(array_values($unpack), count($unpack), 8, 5, true);
        $data = static::DATA . (0 === $this->network->id() ? '_test' : '');

        return encode($data, $words);
    }

	abstract protected function maskPayload(): int;

    protected function computeHex($hash) {
        $payload = $this->maskPayload() | $this->network->id();
        $address = sprintf("%02x", $payload) . $hash;

		$this->addressHex = $address;
        $this->addressBytes = hex2bin($address);
        $this->addressBech32 = $this->computeBech32($this->addressBytes);
    }

    public function getHex() {
        return $this->addressHex;
    }

    public function getBytes() {
        return $this->addressBytes;
    }

    public function getBech32() {
        return $this->addressBech32;
    }
}
