-- #!sqlite
-- #{ bossplugin

-- #  { boss
-- #    { init
CREATE TABLE IF NOT EXISTS Boss(
  Name VARCHAR NOT NULL,
  Object VARCHAR NOT NULL,
  PRIMARY KEY(Name)
);
-- #    }

-- #    { load
SELECT * FROM Boss;
-- #    }

-- #    { reset
DELETE FROM Boss;
-- #    }

-- #    { register
-- #      :name string
-- #      :object string
INSERT OR REPLACE INTO Boss(
  Name,
  Object
) VALUES (
  :name,
  :object
);
-- #    }

-- #    { unregister
-- #      :name string
DELETE FROM Boss
WHERE Name=:name;
-- #    }

-- #    { save
-- #      :name string
-- #      :object string
UPDATE Boss SET
  Object=:object
WHERE Name=:name;
-- #    }

-- #  }

-- #}
