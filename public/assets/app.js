function getToken() {
  try {
    const t = localStorage.getItem('jwt_token');
    if (!t || t === 'undefined' || t === 'null') return null;
    return t;
  } catch { return null; }
}

function setToken(t) {
  try {
    if (typeof t === 'string' && t.trim()) {
      localStorage.setItem('jwt_token', t);
    } else {
      // evita salvar "undefined" / "null" como string
      localStorage.removeItem('jwt_token');
    }
  } catch {}
}

function clearToken() {
  try { localStorage.removeItem('jwt_token'); } catch {}
}

function requireAuth() {
  if (!getToken()) window.location.href = '/';
}

function doLogout() {
  clearToken();
  window.location.href = '/';
}

async function parseResponse(r) {
  const text = await r.text();
  try { return { data: JSON.parse(text), raw: text }; }
  catch { return { data: null, raw: text }; }
}
function api(path, method = 'GET', body = null) {
  const token = getToken();
  const headers = { 'Content-Type': 'application/json' };
  if (token) headers['Authorization'] = 'Bearer ' + token;

  let url = path;
  if (token) url += (path.includes('?') ? '&' : '?') + 'token=' + encodeURIComponent(token);

  return fetch(url, {
    method,
    headers,
    body: body ? JSON.stringify(body) : null
  }).then(async r => {
    const text = await r.text();
    // console.log('api response:', text);
    try {
      const data = JSON.parse(text);
      if (!r.ok) throw data;
      return data ?? {};
    } catch (e) {
      alert('Erro ao processar resposta do servidor.');
      throw e;
    }
  });
}

function loadProducts() {
  const q = $('#q-products').val();
  const status = $('#status-products').val();
  const warranty_min = $('#wmin').val();
  const warranty_max = $('#wmax').val();

  const params = new URLSearchParams();
  if (q) params.append('q', q);
  if (status) params.append('status', status);
  if (warranty_min) params.append('warranty_min', warranty_min);
  if (warranty_max) params.append('warranty_max', warranty_max);

  api('/api/products?' + params.toString())
    .then(products => {
      const tbody = $('#tbl-products tbody');
      tbody.empty();

      products.forEach(p => {
        const row = `<tr>
          <td>${p.id}</td>
          <td>${p.code}</td>
          <td>${p.description}</td>
          <td>${p.status}</td>
          <td>${p.warranty_months}</td>
        </tr>`;
        tbody.append(row);
      });
    })
    .catch(err => console.error('Erro ao carregar produtos', err));
}

