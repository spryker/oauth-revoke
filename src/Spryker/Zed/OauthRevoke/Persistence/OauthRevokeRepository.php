<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Persistence;

use Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshTokenQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\OauthRevoke\Persistence\OauthRevokePersistenceFactory getFactory()
 */
class OauthRevokeRepository extends AbstractRepository implements OauthRevokeRepositoryInterface
{
    public function findRefreshToken(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer
    {
        $oauthTokenCriteriaFilterTransfer->requireIdentifier();

        $oauthRefreshTokenQuery = $this->getFactory()->createRefreshTokenQuery();
        $oauthRefreshTokenQuery = $this->applyRefreshTokenFilters($oauthRefreshTokenQuery, $oauthTokenCriteriaFilterTransfer);

        $oauthRefreshTokenEntity = $oauthRefreshTokenQuery->findOne();

        if (!$oauthRefreshTokenEntity) {
            return null;
        }

        return $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityToOauthRefreshTokenTransfer($oauthRefreshTokenEntity, new OauthRefreshTokenTransfer());
    }

    public function getRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): OauthRefreshTokenCollectionTransfer
    {
        $oauthRefreshTokenQuery = $this->getFactory()->createRefreshTokenQuery();
        $oauthRefreshTokenQuery = $this->applyRefreshTokenFilters($oauthRefreshTokenQuery, $oauthTokenCriteriaFilterTransfer);

        $oauthRefreshTokenCollection = $oauthRefreshTokenQuery->find();

        return $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityCollectionToOauthRefreshTokenTransferCollection($oauthRefreshTokenCollection);
    }

    public function isRefreshTokenRevoked(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): bool
    {
        return $this->getFactory()->createRefreshTokenQuery()
            ->filterByIdentifier($oauthRefreshTokenTransfer->getIdentifier())
            ->filterByRevokedAt(null, Criteria::NOT_EQUAL)
            ->exists();
    }

    protected function applyRefreshTokenFilters(
        SpyOauthRefreshTokenQuery $oauthRefreshTokenQuery,
        OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
    ): SpyOauthRefreshTokenQuery {
        if ($oauthTokenCriteriaFilterTransfer->getIdentifier()) {
            $oauthRefreshTokenQuery->filterByIdentifier($oauthTokenCriteriaFilterTransfer->getIdentifier());
        }

        if ($oauthTokenCriteriaFilterTransfer->getCustomerReference()) {
            $oauthRefreshTokenQuery->filterByCustomerReference($oauthTokenCriteriaFilterTransfer->getCustomerReference());
        }

        $oauthRefreshTokenQuery->filterByRevokedAt(
            null,
            $oauthTokenCriteriaFilterTransfer->getIsRevoked() ? Criteria::ISNOTNULL : Criteria::ISNULL,
        );

        return $oauthRefreshTokenQuery;
    }
}
