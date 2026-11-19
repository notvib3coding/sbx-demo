#!/usr/bin/env python3
"""Print a random cat from The Cat API (https://thecatapi.com).

    python3 cat.py          random cat image URLs (no key needed)
    python3 cat.py --favs   your saved favourites (needs CAT_API_KEY)

The key goes in the x-api-key header, never in the URL.
CAT_DEFAULT_LIMIT sets how many cats to print (default 1).
"""

import argparse
import json
import os
import sys
import urllib.error
import urllib.parse
import urllib.request

API = "https://api.thecatapi.com/v1"


def get(path, params=None, need_key=False):
    url = f"{API}{path}"
    if params:
        url += "?" + urllib.parse.urlencode(params)
    headers = {"User-Agent": "sbx-demo"}
    key = os.environ.get("CAT_API_KEY")
    if key:
        headers["x-api-key"] = key
    elif need_key:
        sys.exit("CAT_API_KEY is not set")
    request = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(request, timeout=10) as response:
            return json.load(response)
    except urllib.error.HTTPError as error:
        sys.exit(f"{error.code} {error.reason} from {path}")
    except urllib.error.URLError as error:
        sys.exit(f"cannot reach {API}: {error.reason}")


def random_cats(limit):
    for cat in get("/images/search", {"limit": limit}):
        print(cat["url"])


def favourites():
    favs = get("/favourites", need_key=True)
    print(f"{len(favs)} favourites")
    for fav in favs:
        print(fav["image"]["url"])


def main():
    parser = argparse.ArgumentParser(description="Print a random cat.")
    parser.add_argument("--favs", action="store_true", help="list your favourites (needs CAT_API_KEY)")
    args = parser.parse_args()
    if args.favs:
        favourites()
    else:
        random_cats(int(os.environ.get("CAT_DEFAULT_LIMIT", "1")))


if __name__ == "__main__":
    main()
