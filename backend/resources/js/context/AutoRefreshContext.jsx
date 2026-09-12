import React, { createContext, useContext, useState, useEffect } from 'react';

const AutoRefreshContext = createContext();

export const useAutoRefresh = () => useContext(AutoRefreshContext);

export const AutoRefreshProvider = ({ children, interval = 10000 }) => {
  const [refreshTrigger, setRefreshTrigger] = useState(0);

  useEffect(() => {
    // Set up an interval to increment the trigger
    const timer = setInterval(() => {
      setRefreshTrigger(prev => prev + 1);
    }, interval);

    return () => clearInterval(timer);
  }, [interval]);

  return (
    <AutoRefreshContext.Provider value={{ refreshTrigger }}>
      {children}
    </AutoRefreshContext.Provider>
  );
};
