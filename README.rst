
==================
TYPO3 Codesnippets
==================


.. contents:: Table of Contents


Prerequisites
=============

Install

1.  `Docker <https://docs.docker.com/get-docker/>`_
2.  `Docker-Compose <https://docs.docker.com/compose/install/>`_
3.  `DDEV <https://ddev.readthedocs.io/en/stable/>`_


.. _installation:

Installation
============

1. Install the project by

   .. code-block:: bash

      ddev composer install

2. Install TYPO3

   .. code-block:: bash

      touch public/FIRST_INSTALL
      ddev launch TYPO3

Run
===

You can run this as CLI command:

.. code-block:: bash

   ddev exec vendor/bin/typo3  restructured_api_tools:php_domain <path to codesnippets.php>

Example
=======

Clone TYPO3CMS-Reference-CoreApi info public/fileadmin:

.. code-block:: bash

   cd public/fileadmin
   git clone git@github.com:TYPO3-Documentation/TYPO3CMS-Reference-CoreApi.git

Then run the codesnippets.php:

.. code-block:: bash

   cd ../..
   ddev exec vendor/bin/typo3  restructured_api_tools:php_domain public/fileadmin/TYPO3CMS-Reference-CoreApi/Documentation/CodeSnippets/
