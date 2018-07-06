export const fetchData = (response) => Promise.resolve(response.data.data);

export const isAdmin = (user) => {
  const roles = user.roles;
  if (!Array.isArray(roles)) return false;

  return roles.find(role => role.slug === 'admin') !== undefined
};