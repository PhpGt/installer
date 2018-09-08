<?php
namespace Gt\Installer;

class CliApplication {
	protected $applicationName;
	protected $arguments;
	protected $commands;
	protected $streams;

	public function __construct(
		string $applicationName,
		CliArgumentList $arguments,
		CliCommand...$commands
	) {
		$this->applicationName = $applicationName;
		$this->arguments = $arguments;
		$this->commands = $commands;

		$this->commands []= new CliHelpCommand(
			$this->applicationName,
			$this->commands
		);

		$this->streams = new CliStreams(
			"php://stdin",
			"php://stdout",
			"php://stderr"
		);
	}

	public function setStreams($in, $out, $error) {
		$this->streams->setStreams($in, $out, $error);
	}

	public function run():void {
		$commandName = $this->arguments->getCommandName();
		$command = $this->findCommandByName($commandName);
		$command->setStreams($this->streams);
		$argumentValueList = $command->getArgumentValueList($this->arguments);

		try {
			$command->checkArguments($this->arguments);
		}
		catch(NotEnoughArgumentsException $exception) {
			$this->streams->writeLine(
				"Not enough arguments passed.",
				CliStreams::ERROR
			);
			$this->streams->writeLine(
				$command->getUsage(),
				CliStreams::ERROR
			);
		}
		catch(MissingRequiredParameterException $exception) {

		}
		catch(MissingRequiredParameterValueException $exception) {

		}

		$command->run($argumentValueList);
	}

	protected function findCommandByName(string $name):CliCommand {
		foreach($this->commands as $command) {
			if($command->getName() !== $name) {
				continue;
			}

			return $command;
		}

		throw new InvalidCliCommandException($name);
	}
}