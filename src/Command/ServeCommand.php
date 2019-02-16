<?php

namespace Gt\Installer\Command;

use Gt\Cli\Argument\ArgumentValueList;
use Gt\Cli\Command\Command;
use Gt\Cli\Parameter\NamedParameter;
use Gt\Cli\Parameter\Parameter;
use Gt\Cli\Stream;

class ServeCommand extends Command {
	public function getName():string {
		return "serve";
	}

	public function getDescription():string {
		return "Run a local development HTTP server";
	}

	/** @return  NamedParameter[] */
	public function getRequiredNamedParameterList():array {
		return [];
	}

	/** @return  NamedParameter[] */
	public function getOptionalNamedParameterList():array {
		return [
			new Parameter(
				true,
				"port",
				"p"
			),
		];
	}

	/** @return  Parameter[] */
	public function getRequiredParameterList():array {
		return [];
	}

	/** @return  Parameter[] */
	public function getOptionalParameterList():array {
		return [];
	}

	public function run(ArgumentValueList $arguments = null):void {
		$gtServeCommand = implode(DIRECTORY_SEPARATOR, [
			"vendor",
			"bin",
			"gt-serve",
		]);

		if(!file_exists($gtServeCommand)) {
			$this->writeLine(
				"The current directory is not a WebEngine application.",
				Stream::ERROR
			);
			return;
		}

		$cmd = implode(" ", [
			$gtServeCommand,
			"--port " . $arguments->get("port", 8080)
		]);

		passthru($cmd);
	}
}