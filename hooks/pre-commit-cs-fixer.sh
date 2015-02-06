#!/bin/sh

PROJECTROOT=`echo $(cd ${0%/*}/../../ && pwd -P)`/
FIXER=php-cs-fixer.phar

if [ ! -e ${PROJECTROOT}${FIXER} ]; then
	echo "PHP-CS-Fixer not available, downloading to ${PROJECTROOT}${FIXER}..."
	curl -s http://cs.sensiolabs.org/get/$FIXER > ${PROJECTROOT}${FIXER}
	echo "Done. First time to check the Coding Standards."
	echo ""
fi

RES=`php ${PROJECTROOT}${FIXER} fix $PROJECTROOT --verbose --dry-run`
if [ "$RES" != "" ]; then
	echo "Coding standards are not correct, cancelling your commit."
	echo ""
	echo $RES
	echo ""
        echo "If you want to fix them run:"
	echo ""
	echo "    php ${PROJECTROOT}${FIXER} fix ${PROJECTROOT} --verbose"
	echo ""
	exit 1
fi
