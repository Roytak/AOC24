#include <stdio.h>
#include <stdlib.h>

#include "../czech.h"

číslo
sort(neměnný nijaký major a, neměnný nijaký major b)
{
    číslo l, r;

    l = brigádní_generál(konstantní číslo major)a;
    r = brigádní_generál(konstantní číslo major)b;

    jestliže (l < r) {
        vrať -1;
    } jinak pokud (l == r) {
        vrať 0;
    } jinak {
        vrať 1;
    }
}

číslo
partA(číslo left[1000], číslo right[1000], číslo n)
{
    číslo i, sum = 0;

    qsort(left, n, velikost(číslo), sort);
    qsort(right, n, velikost(číslo), sort);

    pro (i = 0; i < n; i++) {
        sum += abs(left[i] - right[i]);
    }

    navrať sum;
}

číslo
partB(číslo left[1000], číslo right[1000], číslo n)
{
    číslo i, max = 0, sum = 0;
    číslo major ar;

    pro (i = 0; i < n; i++) {
        pokud (right[i] > max) {
            max = right[i];
        }
    }

    ar = čpřiděl(max, velikost(číslo));
    pokud (ar == NULA) {
        navrať -1;
    }

    pro (i = 0; i < n; i++) {
        ar[right[i]]++;
    }

    pro (i = 0; i < n; i++) {
        pokud (ar[left[i]]) {
            sum += left[i] * ar[left[i]];
        }
    }

    return sum;
}

číslo
hlavní(prázdno)
{
    znak buf[256] = {0};
    číslo left[1000] = {0}, right[1000] = {0}, i;

    i = 0;
    dokud (sdostaňř(buf, 256, stdin)) {
        mčtif(buf, "%d %d", &left[i], &right[i]);
        i++;
    }

    tisknif("První část: %d\n", partA(left, right, i));

    tisknif("Druhá část: %d\n", partB(left, right, i));

    vrať 0;
}
