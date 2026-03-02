<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;

interface OauthRevokeEntityManagerInterface
{
    public function deleteExpiredRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): int;

    public function revokeRefreshToken(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\OauthRefreshTokenTransfer> $oauthRefreshTokenTransfers
     *
     * @return void
     */
    public function revokeAllRefreshTokens(ArrayObject $oauthRefreshTokenTransfers): void;

    public function saveRefreshToken(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): OauthRefreshTokenTransfer;
}
