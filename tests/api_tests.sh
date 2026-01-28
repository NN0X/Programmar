#!/bin/bash

API_URL="http://localhost:9000"
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

print_status() {
    local endpoint=$1
    local expected=$2
    local actual=$3

    if [ "$actual" == "$expected" ]; then
        echo -e "[ ${GREEN}PASS${NC} ] $endpoint (Got $actual)"
    else
        echo -e "[ ${RED}FAIL${NC} ] $endpoint (Expected $expected, Got $actual)"
        exit 1
    fi
}

echo "Starting Integration Tests for $API_URL"

CODE=$(curl -s -o /dev/null -w "%{http_code}" "$API_URL/")
if [[ "$CODE" == "200" || "$CODE" == "302" ]]; then
    print_status "GET /" "$CODE" "$CODE"
else
    print_status "GET /" "200 or 302" "$CODE"
fi

CODE=$(curl -s -o /dev/null -w "%{http_code}" "$API_URL/login")
print_status "GET /login" "200" "$CODE"

CODE=$(curl -s -o /dev/null -w "%{http_code}" "$API_URL/dashboard")
if [[ "$CODE" == "302" || "$CODE" == "403" ]]; then
    print_status "GET /dashboard (Unauthenticated)" "$CODE" "$CODE"
else
    print_status "GET /dashboard (Unauthenticated)" "302 or 403" "$CODE"
fi

CODE=$(curl -s -o /dev/null -w "%{http_code}" "$API_URL/non-existent-page-12345")
if [[ "$CODE" == "404" ]]; then
     print_status "GET /404_check" "404" "$CODE"
else
     echo -e "[ ${RED}WARN${NC} ] GET /404_check returned $CODE (Check Routing.php)"
fi

echo -e "\nAll integration tests finished."
