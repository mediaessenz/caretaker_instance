<?php

namespace Caretaker\CaretakerInstance\Command;

use Caretaker\CaretakerInstance\Operation\OperationManager;
use Caretaker\CaretakerInstance\Security\SecurityManagerInterface;

/***************************************************************
 * Copyright notice
 *
 * (c) 2009-2011 by n@work GmbH and networkteam GmbH
 *
 * All rights reserved
 *
 * This script is part of the Caretaker project. The Caretaker project
 * is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * This is a file of the caretaker project.
 * http://forge.typo3.org/projects/show/extension-caretaker
 *
 * Project sponsored by:
 * n@work GmbH - http://www.work.de
 * networkteam GmbH - http://www.networkteam.com/
 *
 * $Id$
 */

/**
 * The Command Service can execute Commands and
 * is a coarse front service for the caretaker instance.
 *
 * It uses the Security Manager and the Operation Manager
 * to verify and authenticate Command Requests and
 * execute them.
 *
 * @author Martin Ficzel <martin@work.de>
 * @author Thomas Hempel <thomas@work.de>
 * @author Christopher Hlubek <hlubek@networkteam.com>
 * @author Tobias Liebig <liebig@networkteam.com>
 *
 */
class CommandService
{
    /**
     * @var SecurityManagerInterface
     */
    protected $securityManager;

    /**
     * @var OperationManager
     */
    protected $operationManager;

    /**
     * Construct a new Command Service
     *
     * @param OperationManager $operationManager
     * @param SecurityManagerInterface $securityManager
     */
    public function __construct(OperationManager $operationManager, SecurityManagerInterface $securityManager)
    {
        $this->operationManager = $operationManager;
        $this->securityManager = $securityManager;
    }

    /**
     * Execute a Command Request (which consists of multiple Operation keys and parameters).
     *
     * The Command Request is validated, decoded and then executed.
     *
     * @param CommandRequest $commandRequest
     * @return CommandResult The command result object
     */
    public function executeCommand(CommandRequest $commandRequest)
    {
        if ($this->securityManager->validateRequest($commandRequest)) {
            if ($this->securityManager->decodeRequest($commandRequest)) {
                $operations = $commandRequest->getData('operations');

                $results = [];
                foreach ($operations as $operation) {
                    $results[] = $this->operationManager->executeOperation($operation[0], $operation[1]);
                }

                return new CommandResult(CommandResult::status_ok, $results);
            }
            return new CommandResult(CommandResult::status_error, null, 'The request could not be decrypted');
        }
        return new CommandResult(CommandResult::status_error, null, 'The request could not be certified');
    }

    /**
     * Create / request a new session token from the server
     *
     * @param string $clientHostAddress
     * @return string
     */
    public function requestSessionToken($clientHostAddress)
    {
        return $this->securityManager->createSessionToken($clientHostAddress);
    }

    /**
     * Encode / encrypt the command result with the security manager
     *
     * @param CommandResult $commandResult
     * @return string
     */
    public function wrapCommandResult(CommandResult $commandResult)
    {
        $json = $commandResult->toJson();

        return $this->securityManager->encodeResult($json);
    }
}
