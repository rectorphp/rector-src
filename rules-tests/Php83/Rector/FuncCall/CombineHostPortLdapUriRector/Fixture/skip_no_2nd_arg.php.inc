<?php

namespace Rector\Tests\Php83\Rector\FuncCall\CombineHostPortLdapUriRector\Fixture;

class SkipNo2ndArg
{
    public function run()
    {
        $ldapuri = "ldap://ldap.example.com:389";
        $ldapconn = ldap_connect($ldapuri);
    }
}
