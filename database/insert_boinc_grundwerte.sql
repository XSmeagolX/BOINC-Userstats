

--
-- Daten für Tabelle `boinc_grundwerte`
--

INSERT INTO `boinc_grundwerte` (`project`, `url`, `project_homepage_url`, `begin_credits`, `project_status`, `project_shortname`, `total_credits`) VALUES
('Acoustic@home', 'http://www.acousticsathome.ru/boinc/', 'http://www.acousticsathome.ru/boinc/', 0, 1, 'acoustic', 0),
('Amicable Numbers', 'https://sech.me/boinc/Amicable/', 'https://sech.me/boinc/Amicable/', 0, 1, 'amic', 0),
('Asteroids@home', 'https://asteroidsathome.net/boinc/', 'https://asteroidsathome.net/boinc/', 0, 1, 'asteroids', 0),
('Citizen Science Grid', 'https://csgrid.org/', 'https://csgrid.org/', 0, 1, 'csg', 0),
('Collatz Conjecture', 'https://boinc.thesonntags.com/collatz/', 'https://boinc.thesonntags.com/collatz/', 0, 1, 'collatz', 0),
('Cosmology@Home', 'http://www.cosmologyathome.org/', 'http://www.cosmologyathome.org/', 0, 1, 'cosmology', 0),
('DENIS@home', 'http://denis.usj.es/denisathome/', 'http://denis.usj.es/denisathome/', 0, 1, 'denis', 0),
('Einstein@home', 'http://einsteinathome.org/', 'http://einsteinathome.org/', 0, 1, 'einstein', 0),
('Enigma@home', 'http://www.enigmaathome.net/', 'http://www.enigmaathome.net/', 0, 1, 'enigma', 0),
('GPUGRID', 'http://www.gpugrid.net/', 'http://www.gpugrid.net/', 0, 1, 'gpugrid', 0),
('LHC@home', 'https://lhcathome.cern.ch/lhcathome/', 'https://lhcathome.cern.ch/lhcathome/', 0, 1, 'lhcathome', 0),
('MilkyWay@home', 'http://milkyway.cs.rpi.edu/milkyway/', 'http://milkyway.cs.rpi.edu/milkyway/', 0, 1, 'milkyway', 0),
('Moo! Wrapper', 'http://moowrap.net/', 'http://moowrap.net/', 0, 1, 'moo', 0),
('NFS@home', 'http://escatter11.fullerton.edu/nfs/', 'http://escatter11.fullerton.edu/nfs/', 0, 1, 'nfs', 0),
('NumberFields@home', 'https://numberfields.asu.edu/NumberFields/', 'https://numberfields.asu.edu/NumberFields/', 0, 1, 'numberf', 0),
('Primegrid', 'http://www.primegrid.com/', 'http://www.primegrid.com/', 0, 1, 'primegrid', 0),
('RakeSearch', 'http://rake.boincfast.ru/rakesearch/', 'http://rake.boincfast.ru/rakesearch/', 0, 1, 'rakesearch', 0),
('Ralph@home', 'https://ralph.bakerlab.org/', 'https://ralph.bakerlab.org/', 0, 1, 'ralph', 0),
('RNA World', 'http://rnaworld.de/rnaworld/', 'http://rnaworld.de/rnaworld/', 0, 0, 'rnaworld', 0),
('Rosetta@home', 'http://boinc.bakerlab.org/rosetta/', 'http://boinc.bakerlab.org/rosetta/', 0, 1, 'rosetta', 0),
('SETI@home', 'http://setiathome.berkeley.edu/', 'http://setiathome.berkeley.edu/', 0, 1, 'seti', 0),
('TN-Grid', 'http://gene.disi.unitn.it/test/', 'http://gene.disi.unitn.it/test/', 0, 1, 'tngrid', 0),
('Universe', 'http://universeathome.pl/universe/', 'http://universeathome.pl/universe/', 0, 1, 'universe', 0),
('WEP-M+2', 'http://bearnol.is-a-geek.com/wanless2/', 'http://bearnol.is-a-geek.com/wanless2/', 0, 1, 'wepm2', 0),
('World Community Grid', 'http://www.worldcommunitygrid.org/boinc/', 'http://www.worldcommunitygrid.org/', 0, 1, 'wcg', 0),
('WUProp@Home', 'http://wuprop.boinc-af.org/', 'http://wuprop.boinc-af.org/', 0, 1, 'wuprob', 0),
('XANSONS for COD', 'http://xansons4cod.com/xansons4cod/', 'http://xansons4cod.com/xansons4cod/', 0, 1, 'xansons', 0),
('YAFU', 'http://yafu.myfirewall.org/yafu/', 'http://yafu.myfirewall.org/yafu/', 0, 1, 'yafu', 0),
('yoyo@home', 'http://www.rechenkraft.net/yoyo/', 'http://www.rechenkraft.net/yoyo/', 0, 1, 'yoyo', 0);


--
-- Indizes für die Tabelle `boinc_grundwerte`
--
ALTER TABLE `boinc_grundwerte`
  ADD UNIQUE KEY `project_shortname` (`project_shortname`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
