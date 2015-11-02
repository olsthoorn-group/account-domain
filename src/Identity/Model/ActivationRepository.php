<?php

namespace OG\Account\Domain\Identity\Model;

/**
 * Collection oriented repository for activations.
 */
interface ActivationRepository
{
    /**
     * Find an Activation by Alias.
     *
     * @param Email $alias
     *
     * @return Activation|null Activation object or null if it can't be found
     */
    public function findByAlias(Email $alias);

    /**
     * Add a new Activation to the collection.
     *
     * @param Activation $activation
     */
    public function add(Activation $activation);

    /**
     * Update an Activation in the collection.
     *
     * @param Activation $activation
     */
    public function update(Activation $activation);

    /**
     * Delete an Activation by its ActivationCode.
     *
     * @param ActivationCode $code
     */
    public function deleteByCode(ActivationCode $code);

    /**
     * Delete existing Activations for Alias.
     *
     * @param Email $email
     */
    public function deleteExistingActivationsForAlias(Email $email);

    /**
     * Delete all expired Activations.
     */
    public function deleteExpiredActivations();

    /**
     * Return the next identity.
     *
     * @return ActivationId
     */
    public function nextIdentity();
}
