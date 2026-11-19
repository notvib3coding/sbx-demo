# sbx-demo

A tiny app that prints a random cat from [The Cat API](https://thecatapi.com). It is the companion repo for the Not
Vibe Coding video on running a coding agent with full permissions inside Docker Sandboxes.

```sh
python3 cat.py          # a random cat image URL
python3 cat.py --favs   # your saved favourites, needs CAT_API_KEY
```

Settings come from the environment (see `.env.example`):

- `CAT_API_KEY`: your Cat API key. Sent in the `x-api-key` header only.
- `CAT_DEFAULT_LIMIT`: how many cats to print (default 1).

Python 3 standard library only, no dependencies.
