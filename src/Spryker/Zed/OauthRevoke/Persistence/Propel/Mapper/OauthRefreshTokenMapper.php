<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshToken;
use Propel\Runtime\Collection\Collection;

class OauthRefreshTokenMapper
{
    public function mapOauthRefreshTokenTransferToOauthRefreshTokenEntity(
        OauthRefreshTokenTransfer $oauthRefreshTokenTransfer,
        SpyOauthRefreshToken $oauthRefreshTokenEntity
    ): SpyOauthRefreshToken {
        $oauthRefreshTokenEntity->fromArray($oauthRefreshTokenTransfer->toArray());
        /** @var string $idOauthClient */
        $idOauthClient = $oauthRefreshTokenTransfer->getIdOauthClient();
        $oauthRefreshTokenEntity->setFkOauthClient($idOauthClient);

        return $oauthRefreshTokenEntity;
    }

    public function mapOauthRefreshTokenEntityToOauthRefreshTokenTransfer(
        SpyOauthRefreshToken $oauthRefreshTokenEntity,
        OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
    ): OauthRefreshTokenTransfer {
        $oauthRefreshTokenTransfer->fromArray($oauthRefreshTokenEntity->toArray(), true);
        $oauthRefreshTokenTransfer->setIdOauthClient($oauthRefreshTokenEntity->getFkOauthClient());

        return $oauthRefreshTokenTransfer;
    }

    public function mapOauthRefreshTokenEntityCollectionToOauthRefreshTokenTransferCollection(
        Collection $refreshTokenEntities
    ): OauthRefreshTokenCollectionTransfer {
        $oauthRefreshTokenCollectionTransfer = new OauthRefreshTokenCollectionTransfer();
        foreach ($refreshTokenEntities as $refreshTokenEntity) {
            $refreshTokenTransfer = $this->mapOauthRefreshTokenEntityToOauthRefreshTokenTransfer(
                $refreshTokenEntity,
                new OauthRefreshTokenTransfer(),
            );
            $oauthRefreshTokenCollectionTransfer->addOauthRefreshToken($refreshTokenTransfer);
        }

        return $oauthRefreshTokenCollectionTransfer;
    }
}
