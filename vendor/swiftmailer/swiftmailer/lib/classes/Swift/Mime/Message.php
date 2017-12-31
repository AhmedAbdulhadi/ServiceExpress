<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A Message (RFC 2822) object.
 *
 * @author Chris Corbyn
 */
interface Swift_Mime_Message extends Swift_Mime_MimeEntity
{
    /**
     * Generates a valid Message-ID and switches to it.
     *
     * @return string
     */
    public function generateId();

    /**
     * Set the subject of the message.
     *
     * @param string $subject
     */
    public function setSubject($subject);

    /**
     * Get the subject of the message.
     *
     * @return string
     */
    public function getSubject();

    /**
     * Set the origination date of the message as a UNIX timestamp.
     *
     * @param int $date
     */
    public function setDate($date);

    /**
     * Get the origination date of the message as a UNIX timestamp.
     *
     * @return int
     */
    public function getDate();

    /**
     * Set the return-path (bounce-detect) adressModel.
     *
     * @param string $address
     */
    public function setReturnPath($address);

    /**
     * Get the return-path (bounce-detect) adressModel.
     *
     * @return string
     */
    public function getReturnPath();

    /**
     * Set the sender of this message.
     *
     * If multiple addresses are present in the From field, this SHOULD be set.
     *
     * According to RFC 2822 it is a requirement when there are multiple From
     * addresses, but Swift itself does not require it directly.
     *
     * An associative array (with one element!) can be used to provide a display-
     * name: i.e. array('email@adressModel' => 'Real Name').
     *
     * If the second parameter is provided and the first is a string, then $name
     * is associated with the adressModel.
     *
     * @param mixed  $address
     * @param string $name    optional
     */
    public function setSender($address, $name = null);

    /**
     * Get the sender adressModel for this message.
     *
     * This has a higher significance than the From adressModel.
     *
     * @return string
     */
    public function getSender();

    /**
     * Set the From adressModel of this message.
     *
     * It is permissible for multiple From addresses to be set using an array.
     *
     * If multiple From addresses are used, you SHOULD set the Sender adressModel and
     * according to RFC 2822, MUST set the sender adressModel.
     *
     * An array can be used if display names are to be provided: i.e.
     * array('email@adressModel.com' => 'Real Name').
     *
     * If the second parameter is provided and the first is a string, then $name
     * is associated with the adressModel.
     *
     * @param mixed  $addresses
     * @param string $name      optional
     */
    public function setFrom($addresses, $name = null);

    /**
     * Get the From adressModel(es) of this message.
     *
     * This method always returns an associative array where the keys are the
     * addresses.
     *
     * @return string[]
     */
    public function getFrom();

    /**
     * Set the Reply-To adressModel(es).
     *
     * Any replies from the receiver will be sent to this adressModel.
     *
     * It is permissible for multiple reply-to addresses to be set using an array.
     *
     * This method has the same synopsis as {@link setFrom()} and {@link setTo()}.
     *
     * If the second parameter is provided and the first is a string, then $name
     * is associated with the adressModel.
     *
     * @param mixed  $addresses
     * @param string $name      optional
     */
    public function setReplyTo($addresses, $name = null);

    /**
     * Get the Reply-To addresses for this message.
     *
     * This method always returns an associative array where the keys provide the
     * email addresses.
     *
     * @return string[]
     */
    public function getReplyTo();

    /**
     * Set the To adressModel(es).
     *
     * Recipients set in this field will receive a copy of this message.
     *
     * This method has the same synopsis as {@link setFrom()} and {@link setCc()}.
     *
     * If the second parameter is provided and the first is a string, then $name
     * is associated with the adressModel.
     *
     * @param mixed  $addresses
     * @param string $name      optional
     */
    public function setTo($addresses, $name = null);

    /**
     * Get the To addresses for this message.
     *
     * This method always returns an associative array, whereby the keys provide
     * the actual email addresses.
     *
     * @return string[]
     */
    public function getTo();

    /**
     * Set the Cc adressModel(es).
     *
     * Recipients set in this field will receive a 'carbon-copy' of this message.
     *
     * This method has the same synopsis as {@link setFrom()} and {@link setTo()}.
     *
     * @param mixed  $addresses
     * @param string $name      optional
     */
    public function setCc($addresses, $name = null);

    /**
     * Get the Cc addresses for this message.
     *
     * This method always returns an associative array, whereby the keys provide
     * the actual email addresses.
     *
     * @return string[]
     */
    public function getCc();

    /**
     * Set the Bcc adressModel(es).
     *
     * Recipients set in this field will receive a 'blind-carbon-copy' of this
     * message.
     *
     * In other words, they will get the message, but any other recipients of the
     * message will have no such knowledge of their receipt of it.
     *
     * This method has the same synopsis as {@link setFrom()} and {@link setTo()}.
     *
     * @param mixed  $addresses
     * @param string $name      optional
     */
    public function setBcc($addresses, $name = null);

    /**
     * Get the Bcc addresses for this message.
     *
     * This method always returns an associative array, whereby the keys provide
     * the actual email addresses.
     *
     * @return string[]
     */
    public function getBcc();
}
