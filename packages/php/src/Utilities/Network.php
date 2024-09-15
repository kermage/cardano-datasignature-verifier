<?php

/**
 * @package ThemePlate
 */

namespace CardanoDataSignature\Utilities;

enum Network
{
	case Mainnet;
	case Testnet;

	public function id(): int
	{
		return match($this)
		{
			Network::Testnet => 0,
			Network::Mainnet => 1,
		};
	}
}
